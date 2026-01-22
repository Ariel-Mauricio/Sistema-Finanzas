<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Egreso;
use Carbon\Carbon;

class EgresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            'Papelería El Estudiante',
            'Combustibles Petroecuador',
            'Ferretería Los Andes',
            'Servicios Profesionales SAC',
            'Imprenta Gráfica Total',
            'Suministros de Oficina',
            'Transporte Rápido S.A.',
            'Mantenimiento Técnico',
            'Seguridad Privada Elite',
            'Limpieza Profesional'
        ];

        $descripciones = [
            'Compra de suministros de oficina',
            'Combustible para vehículos oficiales',
            'Materiales de construcción y reparación',
            'Servicios profesionales contables',
            'Impresión de documentos oficiales',
            'Papelería y útiles de escritorio',
            'Servicios de transporte y logística',
            'Mantenimiento de equipos y sistemas',
            'Servicios de seguridad privada',
            'Productos de limpieza y aseo'
        ];

        $tiposDocumento = ['01', '02', '03', '04', '05', '06', '07'];
        $bases = ['sur', 'norte', 'sangolqui'];

        // Solo crear 5 registros de ejemplo, no 50
        for ($i = 1; $i <= 5; $i++) {
            $aplicaIva = rand(0, 1) == 1; // 50% probabilidad de aplicar IVA
            $baseIva = rand(100, 5000) / 100; // Entre $1.00 y $50.00
            $iva = $aplicaIva ? $baseIva * 0.15 : 0; // 15% de IVA solo si aplica
            $total = $baseIva + $iva;
            
            Egreso::create([
                'numero_documento' => $i,
                'proveedor' => $proveedores[array_rand($proveedores)],
                'descripcion' => $descripciones[array_rand($descripciones)],
                'tipo_documento' => $tiposDocumento[array_rand($tiposDocumento)],
                'base' => $bases[array_rand($bases)],
                'aplica_iva' => $aplicaIva,
                'base_iva' => $baseIva,
                'iva' => $iva,
                'total' => $total,
                'fecha' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d'),
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30))
            ]);
        }
    }
}
