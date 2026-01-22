<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Máximo de intentos de login permitidos
     */
    protected int $maxAttempts = 5;
    
    /**
     * Minutos de bloqueo después de exceder intentos
     */
    protected int $decayMinutes = 1;

    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login_pro');
    }

    /**
     * Procesar intento de login con seguridad mejorada
     */
    public function login(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:100']
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.'
        ]);

        // Verificar rate limiting
        $this->checkTooManyFailedAttempts($request);

        // Sanitizar email
        $email = filter_var(strtolower(trim($request->email)), FILTER_SANITIZE_EMAIL);

        // Buscar usuario
        $user = User::where('email', $email)->first();

        // Verificar si el usuario existe y está activo
        if (!$user) {
            $this->incrementLoginAttempts($request);
            $this->logFailedAttempt($request, 'Usuario no encontrado');
            
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.'
            ])->withInput(['email' => $request->email]);
        }

        // Verificar si el usuario está activo
        if (!$user->isActive()) {
            $this->logFailedAttempt($request, 'Usuario inactivo: ' . $email);
            
            return back()->withErrors([
                'email' => 'Su cuenta está desactivada. Contacte al administrador.'
            ])->withInput(['email' => $request->email]);
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            $this->incrementLoginAttempts($request);
            $this->logFailedAttempt($request, 'Contraseña incorrecta para: ' . $email);
            
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.'
            ])->withInput(['email' => $request->email]);
        }

        // Login exitoso - limpiar intentos fallidos
        RateLimiter::clear($this->throttleKey($request));

        // Autenticar usuario
        Auth::login($user, $request->boolean('remember'));

        // Regenerar sesión para prevenir session fixation
        $request->session()->regenerate();

        // Actualizar última conexión
        $user->update([
            'last_login' => now(),
            'last_login_ip' => $request->ip()
        ]);

        // Log de acceso exitoso
        Log::info('Login exitoso', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Cerrar sesión de forma segura
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        // Cerrar sesión
        Auth::logout();

        // Invalidar sesión actual
        $request->session()->invalidate();

        // Regenerar token CSRF
        $request->session()->regenerateToken();

        // Log de cierre de sesión
        if ($userId) {
            Log::info('Logout exitoso', ['user_id' => $userId]);
        }

        return redirect()->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }

    /**
     * Verificar si hay demasiados intentos fallidos
     */
    protected function checkTooManyFailedAttempts(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        Log::warning('Rate limit alcanzado', [
            'ip' => $request->ip(),
            'email' => $request->email
        ]);

        throw ValidationException::withMessages([
            'email' => "Demasiados intentos de acceso. Por favor espere " . 
                       ceil($seconds / 60) . " minuto(s) antes de intentar nuevamente."
        ]);
    }

    /**
     * Incrementar contador de intentos fallidos
     */
    protected function incrementLoginAttempts(Request $request): void
    {
        RateLimiter::hit($this->throttleKey($request), $this->decayMinutes * 60);
    }

    /**
     * Generar clave única para rate limiting
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Registrar intento de login fallido
     */
    protected function logFailedAttempt(Request $request, string $reason): void
    {
        Log::warning('Intento de login fallido', [
            'reason' => $reason,
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
    }
}
