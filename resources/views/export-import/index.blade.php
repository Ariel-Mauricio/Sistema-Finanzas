<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportar/Importar Datos - Sistema de Comprobantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-file-invoice-dollar"></i> Sistema de Comprobantes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/comprobantes"><i class="fas fa-receipt"></i> Comprobantes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/egresos"><i class="fas fa-money-bill-wave"></i> Egresos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/multas"><i class="fas fa-gavel"></i> Multas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/export-import"><i class="fas fa-exchange-alt"></i> Sincronizar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-exchange-alt text-primary"></i>
                    Sincronización de Datos - Latacunga
                </h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Sección de Exportación -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-download"></i> Exportar Datos
                        </h4>
                        <small>Para llevar desde la oficina principal a Latacunga</small>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Exporta los comprobantes, egresos y multas de un rango de fechas específico.
                            El archivo se descargará en formato JSON.
                        </p>
                        
                        <form action="{{ route('export-import.exportar') }}" method="GET" id="formExportar">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success btn-lg" onclick="exportarDatos()">
                                    <i class="fas fa-download"></i> Exportar Datos
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="exportarHoy()">
                                    <i class="fas fa-calendar-day"></i> Exportar Solo Hoy
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sección de Importación -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-upload"></i> Importar Datos
                        </h4>
                        <small>Para recibir datos desde Latacunga</small>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Sube el archivo JSON exportado desde Latacunga para agregar los datos 
                            a la base de datos principal.
                        </p>
                        
                        <form action="{{ route('export-import.importar') }}" method="POST" 
                              enctype="multipart/form-data" id="formImportar">
                            @csrf
                            <div class="mb-3">
                                <label for="archivo" class="form-label">Seleccionar Archivo JSON:</label>
                                <input type="file" class="form-control" id="archivo" name="archivo" 
                                       accept=".json" required>
                                <div class="form-text">
                                    Solo archivos JSON exportados desde el sistema. Máximo 10MB.
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info btn-lg">
                                    <i class="fas fa-upload"></i> Importar Datos
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Instrucciones de Uso
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-map-marker-alt text-primary"></i> En Latacunga:</h6>
                                <ol>
                                    <li>Trabaja normalmente con el sistema local</li>
                                    <li>Al final del día, usa <strong>"Exportar Datos"</strong></li>
                                    <li>Selecciona las fechas que necesites exportar</li>
                                    <li>Descarga el archivo JSON</li>
                                    <li>Copia el archivo a una USB o envía por email</li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-building text-success"></i> En la Oficina Principal:</h6>
                                <ol>
                                    <li>Recibe el archivo JSON de Latacunga</li>
                                    <li>Usa <strong>"Importar Datos"</strong></li>
                                    <li>Selecciona el archivo JSON</li>
                                    <li>Haz clic en "Importar Datos"</li>
                                    <li>Los datos se agregarán automáticamente</li>
                                </ol>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-shield-alt"></i>
                            <strong>Nota:</strong> El sistema evita duplicados automáticamente. 
                            Si un comprobante, egreso o multa ya existe, no se importará nuevamente.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportarDatos() {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;
            
            if (!fechaInicio || !fechaFin) {
                alert('Por favor selecciona ambas fechas');
                return;
            }
            
            if (fechaInicio > fechaFin) {
                alert('La fecha de inicio no puede ser mayor que la fecha fin');
                return;
            }
            
            // Crear URL con parámetros
            const url = `{{ route('export-import.exportar') }}?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
            
            // Crear elemento de descarga
            const a = document.createElement('a');
            a.href = url;
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
        
        function exportarHoy() {
            const hoy = new Date().toISOString().split('T')[0];
            document.getElementById('fecha_inicio').value = hoy;
            document.getElementById('fecha_fin').value = hoy;
            exportarDatos();
        }
        
        // Validar archivo antes de enviar
        document.getElementById('formImportar').addEventListener('submit', function(e) {
            const archivo = document.getElementById('archivo').files[0];
            
            if (!archivo) {
                e.preventDefault();
                alert('Por favor selecciona un archivo');
                return;
            }
            
            if (!archivo.name.endsWith('.json')) {
                e.preventDefault();
                alert('Por favor selecciona un archivo JSON válido');
                return;
            }
            
            if (archivo.size > 10 * 1024 * 1024) { // 10MB
                e.preventDefault();
                alert('El archivo es muy grande. Máximo 10MB permitido');
                return;
            }
            
            // Mostrar mensaje de carga
            const btnSubmit = this.querySelector('button[type="submit"]');
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importando...';
            btnSubmit.disabled = true;
        });
    </script>
</body>
</html>
