<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ComprobanteController extends Controller
{
    /**
     * Listar comprobantes
     */
    public function index(Request $request)
    {
        $query = Comprobante::query();

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('cliente', 'like', "%{$buscar}%")
                  ->orWhere('cedula_ruc', 'like', "%{$buscar}%")
                  ->orWhere('numero_comprobante', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        // Filtrar por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        $query->orderBy('created_at', 'desc');
        $comprobantes = $query->paginate(15)->withQueryString();

        // Estadísticas
        $totalIngresos = Comprobante::sum('total');
        $ingresosHoy = Comprobante::whereDate('fecha', today())->sum('total');
        $ingresosMes = Comprobante::whereMonth('fecha', now()->month)
                                  ->whereYear('fecha', now()->year)
                                  ->sum('total');

        return view('comprobantes.index', compact(
            'comprobantes', 'totalIngresos', 'ingresosHoy', 'ingresosMes'
        ));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $tipos = Comprobante::$tipos;
        $metodosPago = Comprobante::$metodosPago;
        return view('comprobantes.create', compact('tipos', 'metodosPago'));
    }

    /**
     * Guardar nuevo comprobante
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:factura,recibo,nota_venta,ticket,otros',
            'cliente' => 'required|string|max:255',
            'cedula_ruc' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'descripcion' => 'required|string|max:1000',
            'subtotal' => 'required|numeric|min:0',
            'iva' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta,cheque,otros',
            'referencia_pago' => 'nullable|string|max:100',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'tipo.required' => 'Seleccione un tipo de comprobante.',
            'cliente.required' => 'El nombre del cliente es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'subtotal.required' => 'El subtotal es obligatorio.',
            'metodo_pago.required' => 'Seleccione un método de pago.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $iva = $request->iva ?? 0;
        $total = $validated['subtotal'] + $iva;

        $comprobante = Comprobante::create([
            'numero_comprobante' => Comprobante::generarNumero(),
            'tipo' => $validated['tipo'],
            'cliente' => $validated['cliente'],
            'cedula_ruc' => $validated['cedula_ruc'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'descripcion' => $validated['descripcion'],
            'subtotal' => $validated['subtotal'],
            'iva' => $iva,
            'total' => $total,
            'metodo_pago' => $validated['metodo_pago'],
            'referencia_pago' => $validated['referencia_pago'],
            'fecha' => $validated['fecha'],
            'observaciones' => $validated['observaciones'],
            'user_id' => auth()->user()?->id ?? 1,
        ]);

        return redirect()->route('comprobantes.index')
            ->with('success', 'Comprobante #' . $comprobante->numero_comprobante . ' creado exitosamente.');
    }

    /**
     * Mostrar comprobante
     */
    public function show(Comprobante $comprobante)
    {
        return view('comprobantes.show', compact('comprobante'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Comprobante $comprobante)
    {
        $tipos = Comprobante::$tipos;
        $metodosPago = Comprobante::$metodosPago;
        return view('comprobantes.edit', compact('comprobante', 'tipos', 'metodosPago'));
    }

    /**
     * Actualizar comprobante
     */
    public function update(Request $request, Comprobante $comprobante)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:factura,recibo,nota_venta,ticket,otros',
            'cliente' => 'required|string|max:255',
            'cedula_ruc' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'descripcion' => 'required|string|max:1000',
            'subtotal' => 'required|numeric|min:0',
            'iva' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta,cheque,otros',
            'referencia_pago' => 'nullable|string|max:100',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $iva = $request->iva ?? 0;
        $total = $validated['subtotal'] + $iva;

        $comprobante->update([
            'tipo' => $validated['tipo'],
            'cliente' => $validated['cliente'],
            'cedula_ruc' => $validated['cedula_ruc'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'descripcion' => $validated['descripcion'],
            'subtotal' => $validated['subtotal'],
            'iva' => $iva,
            'total' => $total,
            'metodo_pago' => $validated['metodo_pago'],
            'referencia_pago' => $validated['referencia_pago'],
            'fecha' => $validated['fecha'],
            'observaciones' => $validated['observaciones'],
        ]);

        return redirect()->route('comprobantes.index')
            ->with('success', 'Comprobante actualizado exitosamente.');
    }

    /**
     * Eliminar comprobante
     */
    public function destroy(Comprobante $comprobante)
    {
        $numero = $comprobante->numero_comprobante;
        $comprobante->delete();

        return redirect()->route('comprobantes.index')
            ->with('success', 'Comprobante #' . $numero . ' eliminado exitosamente.');
    }

    /**
     * Generar PDF del comprobante
     */
    public function generatePDF(Comprobante $comprobante)
    {
        $pdf = Pdf::loadView('comprobantes.pdf', compact('comprobante'));
        return $pdf->download("comprobante_{$comprobante->numero_comprobante}.pdf");
    }
}
