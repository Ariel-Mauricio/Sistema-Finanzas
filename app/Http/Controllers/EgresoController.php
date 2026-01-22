<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EgresoController extends Controller
{
    /**
     * Listar egresos
     */
    public function index(Request $request)
    {
        $query = Egreso::query();

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('proveedor', 'like', "%{$buscar}%")
                  ->orWhere('ruc_proveedor', 'like', "%{$buscar}%")
                  ->orWhere('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        // Filtrar por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtrar por fechas
        if ($request->filled('desde')) {
            $query->whereDate('fecha', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha', '<=', $request->hasta);
        }

        $query->orderBy('created_at', 'desc');
        $egresos = $query->paginate(15)->withQueryString();

        // Estadísticas
        $totalEgresos = Egreso::sum('total');
        $egresosHoy = Egreso::whereDate('fecha', today())->sum('total');
        $egresosMes = Egreso::whereMonth('fecha', now()->month)
                           ->whereYear('fecha', now()->year)
                           ->sum('total');

        return view('egresos.index', compact(
            'egresos', 'totalEgresos', 'egresosHoy', 'egresosMes'
        ));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $tipos = Egreso::$tipos;
        $categorias = Egreso::$categorias;
        $metodosPago = Egreso::$metodosPago;
        return view('egresos.create', compact('tipos', 'categorias', 'metodosPago'));
    }

    /**
     * Guardar nuevo egreso
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:factura,recibo,nota_venta,ticket,nomina,servicios,otros',
            'proveedor' => 'required|string|max:255',
            'ruc_proveedor' => 'nullable|string|max:20',
            'descripcion' => 'required|string|max:1000',
            'categoria' => 'required|in:servicios_basicos,alquiler,sueldos,materiales,transporte,publicidad,mantenimiento,impuestos,seguros,otros',
            'subtotal' => 'required|numeric|min:0',
            'iva' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta,cheque,otros',
            'referencia_pago' => 'nullable|string|max:100',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'tipo.required' => 'Seleccione un tipo de documento.',
            'proveedor.required' => 'El nombre del proveedor es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'categoria.required' => 'Seleccione una categoría.',
            'subtotal.required' => 'El subtotal es obligatorio.',
            'metodo_pago.required' => 'Seleccione un método de pago.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $iva = $request->iva ?? 0;
        $total = $validated['subtotal'] + $iva;

        $egreso = Egreso::create([
            'numero_documento' => Egreso::generarNumero(),
            'tipo' => $validated['tipo'],
            'proveedor' => $validated['proveedor'],
            'ruc_proveedor' => $validated['ruc_proveedor'],
            'descripcion' => $validated['descripcion'],
            'categoria' => $validated['categoria'],
            'subtotal' => $validated['subtotal'],
            'iva' => $iva,
            'total' => $total,
            'metodo_pago' => $validated['metodo_pago'],
            'referencia_pago' => $validated['referencia_pago'],
            'fecha' => $validated['fecha'],
            'observaciones' => $validated['observaciones'],
            'user_id' => auth()->user()?->id ?? 1,
        ]);

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso #' . $egreso->numero_documento . ' registrado exitosamente.');
    }

    /**
     * Mostrar egreso
     */
    public function show(Egreso $egreso)
    {
        return view('egresos.show', compact('egreso'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Egreso $egreso)
    {
        $tipos = Egreso::$tipos;
        $categorias = Egreso::$categorias;
        $metodosPago = Egreso::$metodosPago;
        return view('egresos.edit', compact('egreso', 'tipos', 'categorias', 'metodosPago'));
    }

    /**
     * Actualizar egreso
     */
    public function update(Request $request, Egreso $egreso)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:factura,recibo,nota_venta,ticket,nomina,servicios,otros',
            'proveedor' => 'required|string|max:255',
            'ruc_proveedor' => 'nullable|string|max:20',
            'descripcion' => 'required|string|max:1000',
            'categoria' => 'required|in:servicios_basicos,alquiler,sueldos,materiales,transporte,publicidad,mantenimiento,impuestos,seguros,otros',
            'subtotal' => 'required|numeric|min:0',
            'iva' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta,cheque,otros',
            'referencia_pago' => 'nullable|string|max:100',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $iva = $request->iva ?? 0;
        $total = $validated['subtotal'] + $iva;

        $egreso->update([
            'tipo' => $validated['tipo'],
            'proveedor' => $validated['proveedor'],
            'ruc_proveedor' => $validated['ruc_proveedor'],
            'descripcion' => $validated['descripcion'],
            'categoria' => $validated['categoria'],
            'subtotal' => $validated['subtotal'],
            'iva' => $iva,
            'total' => $total,
            'metodo_pago' => $validated['metodo_pago'],
            'referencia_pago' => $validated['referencia_pago'],
            'fecha' => $validated['fecha'],
            'observaciones' => $validated['observaciones'],
        ]);

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso actualizado exitosamente.');
    }

    /**
     * Eliminar egreso
     */
    public function destroy(Egreso $egreso)
    {
        $numero = $egreso->numero_documento;
        $egreso->delete();

        return redirect()->route('egresos.index')
            ->with('success', 'Egreso #' . $numero . ' eliminado exitosamente.');
    }

    /**
     * Generar PDF del egreso
     */
    public function generarPdf(Egreso $egreso)
    {
        $pdf = Pdf::loadView('egresos.pdf', compact('egreso'));
        return $pdf->download("egreso_{$egreso->numero_documento}.pdf");
    }

    /**
     * Reporte PDF de egresos
     */
    public function reportePdf(Request $request)
    {
        $query = Egreso::query();
        
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }
        
        $egresos = $query->orderBy('fecha', 'desc')->get();
        $pdf = Pdf::loadView('egresos.reporte_pdf', compact('egresos'));
        return $pdf->download('reporte_egresos.pdf');
    }
}
