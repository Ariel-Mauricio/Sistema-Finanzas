<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Comprobantes de Ingreso - SIMPLIFICADO
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_comprobante', 20)->unique();
            $table->enum('tipo', ['factura', 'recibo', 'nota_venta', 'ticket', 'otros'])->default('recibo');
            $table->string('cliente', 255);
            $table->string('cedula_ruc', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('descripcion');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('iva', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta', 'cheque', 'otros'])->default('efectivo');
            $table->string('referencia_pago', 100)->nullable(); // Número de transferencia, cheque, etc.
            $table->date('fecha');
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            
            $table->index('fecha');
            $table->index('tipo');
            $table->index('cliente');
        });

        // Egresos/Gastos - SIMPLIFICADO
        Schema::create('egresos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_documento', 20)->unique();
            $table->enum('tipo', ['factura', 'recibo', 'nota_venta', 'ticket', 'nomina', 'servicios', 'otros'])->default('factura');
            $table->string('proveedor', 255);
            $table->string('ruc_proveedor', 20)->nullable();
            $table->text('descripcion');
            $table->enum('categoria', [
                'servicios_basicos', 'alquiler', 'sueldos', 'materiales', 
                'transporte', 'publicidad', 'mantenimiento', 'impuestos', 
                'seguros', 'otros'
            ])->default('otros');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('iva', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta', 'cheque', 'otros'])->default('efectivo');
            $table->string('referencia_pago', 100)->nullable();
            $table->date('fecha');
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            
            $table->index('fecha');
            $table->index('tipo');
            $table->index('categoria');
            $table->index('proveedor');
        });

        // Multas - SIMPLIFICADO
        Schema::create('multas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_documento', 20)->unique();
            $table->string('persona', 255);
            $table->string('aplicado_por', 255);
            $table->text('motivo');
            $table->decimal('valor', 12, 2);
            $table->date('fecha');
            $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Configuración del Sistema
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique();
            $table->text('valor')->nullable();
            $table->string('tipo', 50)->default('string'); // string, boolean, integer, json
            $table->string('grupo', 50)->default('general');
            $table->string('descripcion', 255)->nullable();
            $table->timestamps();
        });

        // Cache table (requerido por Laravel)
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Jobs table (requerido por Laravel)
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('configuracion');
        Schema::dropIfExists('multas');
        Schema::dropIfExists('egresos');
        Schema::dropIfExists('comprobantes');
    }
};
