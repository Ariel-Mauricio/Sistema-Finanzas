<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Multa;
use Carbon\Carbon;

class MultaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multas = [
            [
                'nombre_multado' => 'Juan Carlos Pérez',
                'aplicado_por' => 'Inspector López',
                'motivo' => 'Estacionamiento en lugar prohibido en zona militar',
                'valor' => 50.00,
                'fecha' => Carbon::now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'nombre_multado' => 'María Elena García',
                'aplicado_por' => 'Sargento Morales',
                'motivo' => 'Ingreso no autorizado a instalaciones restringidas',
                'valor' => 150.00,
                'fecha' => Carbon::now()->subDays(3)->format('Y-m-d'),
            ],
            [
                'nombre_multado' => 'Carlos Alberto Ruiz',
                'aplicado_por' => 'Capitán Rodríguez',
                'motivo' => 'Portación indebida de credenciales falsas',
                'valor' => 200.00,
                'fecha' => Carbon::now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'nombre_multado' => 'Ana Sofía Mendoza',
                'aplicado_por' => 'Teniente Vargas',
                'motivo' => 'Violación de protocolo de seguridad en el perímetro',
                'valor' => 75.00,
                'fecha' => Carbon::now()->subDays(1)->format('Y-m-d'),
            ],
            [
                'nombre_multado' => 'Roberto Andrés Silva',
                'aplicado_por' => 'Inspector General',
                'motivo' => 'Comportamiento inadecuado durante inspección rutinaria',
                'valor' => 100.00,
                'fecha' => Carbon::now()->format('Y-m-d'),
            ],
            [
                'nombre_multado' => 'Lucía Fernández',
                'aplicado_por' => 'Comandante Torres',
                'motivo' => 'Falta grave: introducción de material no autorizado',
                'valor' => 300.00,
                'fecha' => Carbon::now()->subWeek()->format('Y-m-d'),
            ],
            [
                'nombre_multado' => 'Diego Alejandro Cruz',
                'aplicado_por' => 'Oficial Herrera',
                'motivo' => 'Incumplimiento de horarios establecidos en múltiples ocasiones',
                'valor' => 80.00,
                'fecha' => Carbon::now()->subWeeks(2)->format('Y-m-d'),
            ]
        ];

        foreach ($multas as $multaData) {
            $multa = new Multa();
            $multa->numero_documento = Multa::generarNumeroDocumento();
            $multa->nombre_multado = $multaData['nombre_multado'];
            $multa->aplicado_por = $multaData['aplicado_por'];
            $multa->motivo = $multaData['motivo'];
            $multa->valor = $multaData['valor'];
            $multa->fecha = $multaData['fecha'];
            $multa->save();
        }
    }
}
