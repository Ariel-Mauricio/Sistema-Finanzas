<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionSistema;
use App\Models\User;
use App\Models\Comprobante;
use App\Models\Egreso;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ConfiguracionController extends Controller
{
    /**
     * Configuración General
     */
    public function index()
    {
        $configuraciones = ConfiguracionSistema::all()->groupBy('grupo');
        
        // Estadísticas del sistema
        $estadisticas = [
            'total_comprobantes' => Comprobante::count(),
            'total_egresos' => Egreso::count(),
            'total_usuarios' => User::count(),
            'usuarios_activos' => User::where('active', true)->count(),
        ];

        return view('configuracion.index', compact('configuraciones', 'estadisticas'));
    }

    /**
     * Guardar configuraciones generales
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'configuraciones' => 'required|array'
        ]);

        foreach ($request->configuraciones as $clave => $valor) {
            ConfiguracionSistema::updateOrCreate(
                ['clave' => $clave],
                ['valor' => $valor]
            );
            Cache::forget("config_{$clave}");
        }

        return redirect()->route('configuracion.index')
            ->with('success', 'Configuración guardada correctamente');
    }

    /**
     * Configuración de Empresa
     */
    public function empresa()
    {
        $empresa = [
            'nombre' => ConfiguracionSistema::obtener('empresa_nombre', 'FinanzaPro'),
            'ruc' => ConfiguracionSistema::obtener('empresa_ruc', ''),
            'direccion' => ConfiguracionSistema::obtener('empresa_direccion', ''),
            'telefono' => ConfiguracionSistema::obtener('empresa_telefono', ''),
            'email' => ConfiguracionSistema::obtener('empresa_email', ''),
            'logo' => ConfiguracionSistema::obtener('empresa_logo', ''),
            'slogan' => ConfiguracionSistema::obtener('empresa_slogan', ''),
            'ciudad' => ConfiguracionSistema::obtener('empresa_ciudad', ''),
            'provincia' => ConfiguracionSistema::obtener('empresa_provincia', ''),
        ];

        return view('configuracion.empresa', compact('empresa'));
    }

    /**
     * Guardar datos de empresa
     */
    public function guardarEmpresa(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:13',
            'direccion' => 'nullable|string|max:500',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'slogan' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Guardar configuraciones
        $campos = ['nombre', 'ruc', 'direccion', 'telefono', 'email', 'slogan', 'ciudad', 'provincia'];
        foreach ($campos as $campo) {
            ConfiguracionSistema::establecer("empresa_{$campo}", $request->$campo ?? '', 'string', 'empresa');
        }

        // Manejar logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            ConfiguracionSistema::establecer('empresa_logo', $path, 'string', 'empresa');
        }

        return redirect()->route('configuracion.empresa')
            ->with('success', 'Información de la empresa actualizada correctamente');
    }

    /**
     * Gestión de Usuarios
     */
    public function usuarios()
    {
        $usuarios = User::orderBy('created_at', 'desc')->get();
        return view('configuracion.usuarios', compact('usuarios'));
    }

    /**
     * Crear nuevo usuario
     */
    public function crearUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            'role' => 'required|in:admin,contador,auxiliar,usuario'
        ], [
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un número.',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'active' => true,
            'email_verified_at' => now(),
        ]);

        Log::info('Usuario creado', ['admin' => auth()->user()?->id ?? 0, 'nuevo_usuario' => $user->id]);

        return redirect()->route('configuracion.usuarios')
            ->with('success', "Usuario {$user->name} creado correctamente");
    }

    /**
     * Actualizar usuario existente
     */
    public function actualizarUsuario(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'role' => 'required|in:admin,contador,auxiliar,usuario'
        ]);

        $usuario->update([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/'
            ]);
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        Log::info('Usuario actualizado', ['admin' => auth()->user()?->id ?? 0, 'usuario' => $usuario->id]);

        return redirect()->route('configuracion.usuarios')
            ->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleUsuario(User $usuario)
    {
        // No permitir desactivarse a sí mismo
        $authId = auth()->user()?->id;
        if ($usuario->id === $authId) {
            return redirect()->route('configuracion.usuarios')
                ->with('error', 'No puedes desactivar tu propia cuenta');
        }

        $usuario->update(['active' => !$usuario->active]);
        $estado = $usuario->active ? 'activado' : 'desactivado';
        
        Log::info("Usuario {$estado}", ['admin' => auth()->user()?->id ?? 0, 'usuario' => $usuario->id]);

        return redirect()->route('configuracion.usuarios')
            ->with('success', "Usuario {$estado} correctamente");
    }

    /**
     * Eliminar usuario
     */
    public function eliminarUsuario(User $usuario)
    {
        $authId = auth()->user()?->id;
        if ($usuario->id === $authId) {
            return redirect()->route('configuracion.usuarios')
                ->with('error', 'No puedes eliminar tu propia cuenta');
        }

        $nombre = $usuario->name;
        $usuario->delete();
        
        Log::info('Usuario eliminado', ['admin' => auth()->user()?->id ?? 0, 'usuario_eliminado' => $nombre]);

        return redirect()->route('configuracion.usuarios')
            ->with('success', "Usuario {$nombre} eliminado correctamente");
    }

    /**
     * Configuración de Notificaciones
     */
    public function notificaciones()
    {
        $notificaciones = [
            'email_nuevo_comprobante' => ConfiguracionSistema::obtener('notif_email_comprobante', false),
            'email_nuevo_egreso' => ConfiguracionSistema::obtener('notif_email_egreso', false),
            'email_backup' => ConfiguracionSistema::obtener('notif_email_backup', false),
            'email_login_fallido' => ConfiguracionSistema::obtener('notif_login_fallido', true),
            'limite_alertas' => ConfiguracionSistema::obtener('notif_limite_alertas', 1000),
        ];

        return view('configuracion.notificaciones', compact('notificaciones'));
    }

    /**
     * Guardar configuración de notificaciones
     */
    public function guardarNotificaciones(Request $request)
    {
        $campos = [
            'email_nuevo_comprobante' => 'notif_email_comprobante',
            'email_nuevo_egreso' => 'notif_email_egreso', 
            'email_backup' => 'notif_email_backup',
            'email_login_fallido' => 'notif_login_fallido',
        ];

        foreach ($campos as $input => $clave) {
            ConfiguracionSistema::establecer($clave, $request->boolean($input), 'boolean', 'notificaciones');
        }

        if ($request->filled('limite_alertas')) {
            ConfiguracionSistema::establecer('notif_limite_alertas', (int)$request->limite_alertas, 'integer', 'notificaciones');
        }

        return redirect()->route('configuracion.notificaciones')
            ->with('success', 'Configuración de notificaciones guardada');
    }

    /**
     * Gestión de Respaldos
     */
    public function respaldos()
    {
        // Listar backups existentes
        $backups = [];
        $backupPath = storage_path('app/backups');
        
        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/*.sql');
            foreach ($files as $file) {
                $backups[] = [
                    'nombre' => basename($file),
                    'tamanio' => $this->formatBytes(filesize($file)),
                    'fecha' => Carbon::createFromTimestamp(filemtime($file))->format('d/m/Y H:i'),
                    'timestamp' => filemtime($file)
                ];
            }
            // Ordenar por fecha desc
            usort($backups, fn($a, $b) => $b['timestamp'] - $a['timestamp']);
        }

        return view('configuracion.respaldos', compact('backups'));
    }

    /**
     * Crear backup de la base de datos
     */
    public function crearBackup()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $path = $backupPath . '/' . $filename;

            // Obtener configuración de BD
            $host = config('database.connections.mysql.host');
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Comando mysqldump
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > "%s" 2>&1',
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                $path
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !file_exists($path) || filesize($path) < 100) {
                // Método alternativo con PHP
                $this->backupWithPHP($path, $database);
            }

            Log::info('Backup creado', ['archivo' => $filename, 'admin' => auth()->user()?->id ?? 0]);

            return redirect()->route('configuracion.respaldos')
                ->with('success', "Backup creado: {$filename}");

        } catch (\Exception $e) {
            Log::error('Error al crear backup: ' . $e->getMessage());
            return redirect()->route('configuracion.respaldos')
                ->with('error', 'Error al crear el backup: ' . $e->getMessage());
        }
    }

    /**
     * Backup alternativo usando PHP puro
     */
    private function backupWithPHP($path, $database)
    {
        $tables = DB::select('SHOW TABLES');
        $tableKey = "Tables_in_{$database}";
        
        $sql = "-- Backup generado el " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Base de datos: {$database}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // Estructura
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            // Datos
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $columns = array_keys((array)$rows->first());
                $columnList = '`' . implode('`, `', $columns) . '`';
                
                foreach ($rows as $row) {
                    $values = array_map(function($value) {
                        if ($value === null) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, (array)$row);
                    
                    $sql .= "INSERT INTO `{$tableName}` ({$columnList}) VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        file_put_contents($path, $sql);
    }

    /**
     * Descargar backup
     */
    public function descargarBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (!file_exists($path)) {
            return redirect()->route('configuracion.respaldos')
                ->with('error', 'El archivo no existe');
        }

        return response()->download($path);
    }

    /**
     * Eliminar backup
     */
    public function eliminarBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);
            Log::info('Backup eliminado', ['archivo' => $filename, 'admin' => auth()->user()?->id ?? 0]);
            return redirect()->route('configuracion.respaldos')
                ->with('success', 'Backup eliminado correctamente');
        }

        return redirect()->route('configuracion.respaldos')
            ->with('error', 'El archivo no existe');
    }

    /**
     * Restaurar backup
     */
    public function restaurarBackup(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:sql,txt|max:51200'
        ]);

        try {
            $contenido = file_get_contents($request->file('archivo')->getPathname());
            
            // Ejecutar SQL
            DB::unprepared($contenido);

            Log::info('Backup restaurado', ['admin' => auth()->user()?->id ?? 0]);

            return redirect()->route('configuracion.respaldos')
                ->with('success', 'Backup restaurado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al restaurar backup: ' . $e->getMessage());
            return redirect()->route('configuracion.respaldos')
                ->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }

    /**
     * Configuración de Seguridad
     */
    public function seguridad()
    {
        $seguridad = [
            'intentos_login' => ConfiguracionSistema::obtener('seg_intentos_login', 5),
            'bloqueo_minutos' => ConfiguracionSistema::obtener('seg_bloqueo_minutos', 15),
            'session_timeout' => ConfiguracionSistema::obtener('seg_session_timeout', 120),
            'forzar_https' => ConfiguracionSistema::obtener('seg_forzar_https', false),
            'log_accesos' => ConfiguracionSistema::obtener('seg_log_accesos', true),
            'password_expira' => ConfiguracionSistema::obtener('seg_password_expira', 90),
        ];

        // Últimos accesos
        $ultimosAccesos = User::whereNotNull('last_login')
            ->orderBy('last_login', 'desc')
            ->limit(10)
            ->get(['name', 'email', 'last_login', 'last_login_ip']);

        return view('configuracion.seguridad', compact('seguridad', 'ultimosAccesos'));
    }

    /**
     * Guardar configuración de seguridad
     */
    public function guardarSeguridad(Request $request)
    {
        $request->validate([
            'intentos_login' => 'required|integer|min:3|max:10',
            'bloqueo_minutos' => 'required|integer|min:5|max:60',
            'session_timeout' => 'required|integer|min:30|max:480',
            'password_expira' => 'required|integer|min:30|max:365',
        ]);

        ConfiguracionSistema::establecer('seg_intentos_login', $request->intentos_login, 'integer', 'seguridad');
        ConfiguracionSistema::establecer('seg_bloqueo_minutos', $request->bloqueo_minutos, 'integer', 'seguridad');
        ConfiguracionSistema::establecer('seg_session_timeout', $request->session_timeout, 'integer', 'seguridad');
        ConfiguracionSistema::establecer('seg_forzar_https', $request->boolean('forzar_https'), 'boolean', 'seguridad');
        ConfiguracionSistema::establecer('seg_log_accesos', $request->boolean('log_accesos'), 'boolean', 'seguridad');
        ConfiguracionSistema::establecer('seg_password_expira', $request->password_expira, 'integer', 'seguridad');

        return redirect()->route('configuracion.seguridad')
            ->with('success', 'Configuración de seguridad guardada');
    }

    /**
     * Helper para formatear bytes
     */
    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), 2) . ' ' . $units[$pow];
    }
}
