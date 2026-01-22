<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comprobante;
use Carbon\Carbon;

class ComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comprobantes = [
            [
                'numero_comprobante' => '000001',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'cedula' => '1234567890',
                'telefono' => '0987654321',
                'direccion' => 'Av. Principal 123, Quito',
                'tipo_comprobante' => 'ingreso',
                'cantidad' => 1,
                'precio' => 250.00,
                'valor_total' => 250.00,
                'fecha' => Carbon::now()->subDays(5),
                'observaciones' => 'Pago completo realizado',
            ],
            [
                'numero_comprobante' => '000002',
                'nombre' => 'María',
                'apellido' => 'González',
                'cedula' => '0987654321',
                'telefono' => '0123456789',
                'direccion' => 'Calle Secundaria 456, Guayaquil',
                'tipo_comprobante' => 'pensiones',
                'cantidad' => 1,
                'precio' => 180.00,
                'valor_total' => 180.00,
                'fecha' => Carbon::now()->subDays(3),
                'observaciones' => 'Pensión mensual',
            ],
            [
                'numero_comprobante' => '000003',
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez',
                'cedula' => '1122334455',
                'telefono' => '0999888777',
                'direccion' => 'Barrio La Paz, Cuenca',
                'tipo_comprobante' => 'revisiones_medicas',
                'cantidad' => 1,
                'precio' => 75.00,
                'valor_total' => 75.00,
                'fecha' => Carbon::now()->subDays(1),
                'observaciones' => 'Revisión médica anual',
            ],
            [
                'numero_comprobante' => '000004',
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'cedula' => '5566778899',
                'telefono' => '0111222333',
                'direccion' => 'Sector Norte, Ambato',
                'tipo_comprobante' => 'extras',
                'cantidad' => 2,
                'precio' => 50.00,
                'valor_total' => 100.00,
                'fecha' => Carbon::now(),
                'descripcion_extra' => 'Materiales adicionales para el curso',
                'observaciones' => 'Pago por materiales extras solicitados',
            ],
            [
                'numero_comprobante' => '000005',
                'nombre' => 'Luis',
                'apellido' => 'Vera',
                'cedula' => '3344556677',
                'telefono' => '0991122334',
                'direccion' => 'Av. Amazonas 789, Quito',
                'tipo_comprobante' => 'reserva_cupo',
                'cantidad' => 1,
                'precio' => 120.00,
                'valor_total' => 120.00,
                'fecha' => Carbon::now(),
                'observaciones' => 'Reserva de cupo para el curso de verano',
            ],
        ];

        foreach ($comprobantes as $comprobante) {
            Comprobante::create($comprobante);
        }
    }
}
