<?php

namespace App\Http\Controllers;

use App\Models\Multa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MultaController extends Controller
{
    /**
     * Listar multas
     */
    public function index(Request $request)
    {
        $query = Multa::query();

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('persona', 'like', "%{$buscar}%")
                  ->orWhere('numero_documento', 'like', "%{$buscar}%")
                  ->orWhere('motivo', 'like', "%{$buscar}%");
            });
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $query->orderBy('created_at', 'desc');
        $multas = $query->paginate(15)->withQueryString();

        // Estadísticas
        $totalMultas = Multa::sum('valor');
        $pendientes = Multa::where('estado', 'pendiente')->sum('valor');
        $pagadas = Multa::where('estado', 'pagada')->sum('valor');

        return view('multas.index', compact(
            'multas', 'totalMultas', 'pendientes', 'pagadas'
        ));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('multas.create');
    }

    /**
     * Guardar nueva multa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'persona' => 'required|string|max:255',
            'aplicado_por' => 'required|string|max:255',
            'motivo' => 'required|string|max:1000',
            'valor' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
        ], [
            'persona.required' => 'El nombre de la persona es obligatorio.',
            'aplicado_por.required' => 'Indique quién aplica la multa.',
            'motivo.required' => 'El motivo de la multa es obligatorio.',
            'valor.required' => 'El valor de la multa es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
        ]);

        $multa = Multa::create([
            'numero_documento' => Multa::generarNumero(),
            'persona' => $validated['persona'],
            'aplicado_por' => $validated['aplicado_por'],
            'motivo' => $validated['motivo'],
            'valor' => $validated['valor'],
            'fecha' => $validated['fecha'],
            'estado' => 'pendiente',
            'user_id' => auth()->user()?->id ?? 1,
        ]);

        return redirect()->route('multas.index')
            ->with('success', 'Multa #' . $multa->numero_documento . ' registrada exitosamente.');
    }

    /**
     * Mostrar multa
     */
    public function show(Multa $multa)
    {
        return view('multas.show', compact('multa'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Multa $multa)
    {
        return view('multas.edit', compact('multa'));
    }

    /**
     * Actualizar multa
     */
    public function update(Request $request, Multa $multa)
    {
        $validated = $request->validate([
            'persona' => 'required|string|max:255',
            'aplicado_por' => 'required|string|max:255',
            'motivo' => 'required|string|max:1000',
            'valor' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,pagada,anulada',
        ]);

        $multa->update($validated);

        return redirect()->route('multas.index')
            ->with('success', 'Multa actualizada exitosamente.');
    }

    /**
     * Eliminar multa
     */
    public function destroy(Multa $multa)
    {
        $numero = $multa->numero_documento;
        $multa->delete();

        return redirect()->route('multas.index')
            ->with('success', 'Multa #' . $numero . ' eliminada exitosamente.');
    }

    /**
     * Generar PDF de la multa
     */
    public function generarPdf(Multa $multa)
    {
        $pdf = Pdf::loadView('multas.pdf', compact('multa'));
        return $pdf->download("multa_{$multa->numero_documento}.pdf");
    }

    /**
     * Reporte PDF de multas
     */
    public function reportePdf()
    {
        $multas = Multa::orderBy('fecha', 'desc')->get();
        $pdf = Pdf::loadView('multas.reporte_pdf', compact('multas'));
        return $pdf->download('reporte_multas.pdf');
    }
}
