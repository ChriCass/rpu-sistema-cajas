<div>
    {{-- In work, do what you enjoy. --}}

    <div class="flex flex-col space-y-6">
        <!-- Encabezado -->
        <div class="bg-teal-600 text-white p-4 rounded-t-lg shadow-md mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold uppercase">{{ auth()->user()->empresa->nombre ?? 'METAMSUR S.A.C.' }}</h2>
                    <p class="text-sm">RUC: {{ auth()->user()->empresa->ruc ?? '20506666558' }}</p>
                </div>
                <div class="flex-1 text-center">
                    <h1 class="text-xl font-bold uppercase">GRÁFICOS DE VENTAS</h1>
                </div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Filtros</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="anio" class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                    <select id="anio" wire:model.live="anioSeleccionado" class="border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm w-full">
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="mes" class="block text-sm font-medium text-gray-700 mb-1">Mes</label>
                    <select id="mes" wire:model.live="mesSeleccionado" class="border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm w-full">
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Mensaje de ayuda para recargar si los gráficos no aparecen -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Si los gráficos no aparecen correctamente, por favor <button onclick="location.reload()" class="font-medium underline hover:text-blue-800 focus:outline-none">recargue la página</button>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contenedor principal de gráficos -->
        <div x-data="ventasCharts(@js($datosGrafico))" x-init="initCharts()" class="grid grid-cols-1 gap-6">
            <!-- Primera fila: Gráficos de ventas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gráfico 1: Importes Diarios -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4" x-text="titulos.importeDiario"></h2>
                    <div id="graficoImporteDiario" style="height: 350px;"></div>
                </div>
                
                <!-- Gráfico 2: Tendencia Semanal -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4" x-text="titulos.tendenciaSemanal"></h2>
                    <div id="graficoTendenciaSemanal" style="height: 350px;"></div>
                </div>
                
                <!-- Gráfico 3: Comparación Mensual -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4" x-text="titulos.comparacionMensual"></h2>
                    <div id="graficoComparacionMensual" style="height: 350px;"></div>
                </div>
                
                <!-- Gráfico 4: Rendimiento Anual -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4" x-text="titulos.rendimientoAnual"></h2>
                    <div id="graficoRendimientoAnual" style="height: 350px;"></div>
                </div>
            </div>
            
            <!-- Segunda fila: Gráficos de productividad -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gráfico 6: Distribución de Horas -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4" x-text="titulos.distribucionHoras"></h2>
                    <div id="graficoDistribucionHoras" style="height: 350px;"></div>
                </div>
                
                <!-- Gráfico 7: Ventas por Unidad -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4" x-text="titulos.ventasPorUnidad"></h2>
                    <div id="graficoVentasPorUnidad" style="height: 350px;"></div>
                </div>
            </div>
            
            <!-- Tercera fila: Gráfico de Ventas por Tipo de Venta -->
            <div class="grid grid-cols-1 gap-6 mt-6">
                <!-- Gráfico 8: Ventas por Tipo de Venta -->
        <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4" x-text="titulos.ventasPorTipoVenta"></h2>
                    <div id="graficoVentasPorTipoVenta" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ventasCharts', (inicialData) => ({
                // Referencias a los gráficos
                charts: {
                    importeDiario: null,
                    tendenciaSemanal: null,
                    comparacionMensual: null,
                    rendimientoAnual: null,
                    distribucionHoras: null,
                    ventasPorUnidad: null,
                    ventasPorTipoVenta: null
                },
                
                // Datos para los gráficos
                datos: {
                    importeDiario: inicialData?.importeDiario?.datos || [],
                    tendenciaSemanal: inicialData?.tendenciaSemanal?.datos || [],
                    comparacionMensual: inicialData?.comparacionMensual?.datos || [],
                    rendimientoAnual: inicialData?.rendimientoAnual?.datos || [],
                    distribucionHoras: inicialData?.distribucionHoras?.datos || [],
                    ventasPorUnidad: inicialData?.ventasPorUnidad?.datos || [],
                    ventasPorTipoVenta: inicialData?.ventasPorTipoVenta?.datos || []
                },
                
                // Títulos para los gráficos
                titulos: {
                    importeDiario: inicialData?.importeDiario?.titulo || 'Importes Diarios',
                    tendenciaSemanal: inicialData?.tendenciaSemanal?.titulo || 'Tendencia Semanal',
                    comparacionMensual: inicialData?.comparacionMensual?.titulo || 'Comparación Mensual',
                    rendimientoAnual: inicialData?.rendimientoAnual?.titulo || 'Rendimiento Anual',
                    distribucionHoras: inicialData?.distribucionHoras?.titulo || 'Distribución de Horas',
                    ventasPorUnidad: inicialData?.ventasPorUnidad?.titulo || 'Ventas por Unidad',
                    ventasPorTipoVenta: inicialData?.ventasPorTipoVenta?.titulo || 'Ventas por Tipo de Venta'
                },
                
                // Inicializar todos los gráficos
                initCharts() {
                    // Crear todos los gráficos
                    this.renderAllCharts();
                    
                    // Escuchar eventos de Livewire
                    this.$wire.$on('datosRecargados', (datosActualizados) => {
                        console.log('Datos recibidos en evento datosRecargados:', datosActualizados);
                        
                        if (datosActualizados && typeof datosActualizados === 'object') {
                            // Actualizar todos los datos
                            this.updateAllData(datosActualizados);
                            
                            // Recrear todos los gráficos
                            setTimeout(() => {
                                this.renderAllCharts();
                            }, 100);
                        } else {
                            console.error('Formato de datos inválido:', datosActualizados);
                        }
                    });
                    
                    // Escuchar evento de refresh
                    this.$wire.$on('refresh', () => {
                        console.log('Evento refresh recibido, recargando gráficos...');
                        this.$wire.call('getDatosGrafico').then(result => {
                            console.log('Datos refrescados:', result);
                            if (result && typeof result === 'object') {
                                // Actualizar todos los datos
                                this.updateAllData(result);
                                
                                // Recrear todos los gráficos
                                setTimeout(() => {
                                    this.renderAllCharts();
                                }, 100);
                            }
                        });
                    });
                    
                    // También escuchar cambios directos en los selectores
                    document.querySelectorAll('#anio, #mes').forEach(select => {
                        select.addEventListener('change', () => {
                            console.log(`Cambio detectado en selector ${select.id}`);
                            // Dar tiempo a Livewire para actualizar los datos
                            setTimeout(() => {
                                this.$wire.call('getDatosGrafico').then(result => {
                                    console.log('Datos obtenidos directamente:', result);
                                    if (result && typeof result === 'object') {
                                        // Actualizar todos los datos
                                        this.updateAllData(result);
                                        
                                        // Recrear todos los gráficos
                                        this.renderAllCharts();
                                    }
                                });
                            }, 300);
                        });
                    });
                },
                
                // Actualizar todos los datos
                updateAllData(newData) {
                    // Actualizar datos para cada gráfico
                    if (newData.importeDiario) {
                        this.datos.importeDiario = Array.isArray(newData.importeDiario.datos) ? newData.importeDiario.datos : [];
                        this.titulos.importeDiario = newData.importeDiario.titulo || this.titulos.importeDiario;
                    }
                    
                    if (newData.tendenciaSemanal) {
                        this.datos.tendenciaSemanal = Array.isArray(newData.tendenciaSemanal.datos) ? newData.tendenciaSemanal.datos : [];
                        this.titulos.tendenciaSemanal = newData.tendenciaSemanal.titulo || this.titulos.tendenciaSemanal;
                    }
                    
                    if (newData.comparacionMensual) {
                        this.datos.comparacionMensual = Array.isArray(newData.comparacionMensual.datos) ? newData.comparacionMensual.datos : [];
                        this.titulos.comparacionMensual = newData.comparacionMensual.titulo || this.titulos.comparacionMensual;
                    }
                    
                    if (newData.rendimientoAnual) {
                        this.datos.rendimientoAnual = Array.isArray(newData.rendimientoAnual.datos) ? newData.rendimientoAnual.datos : [];
                        this.titulos.rendimientoAnual = newData.rendimientoAnual.titulo || this.titulos.rendimientoAnual;
                    }
                    
                    if (newData.distribucionHoras) {
                        this.datos.distribucionHoras = Array.isArray(newData.distribucionHoras.datos) ? newData.distribucionHoras.datos : [];
                        this.titulos.distribucionHoras = newData.distribucionHoras.titulo || this.titulos.distribucionHoras;
                    }
                    
                    if (newData.ventasPorUnidad) {
                        this.datos.ventasPorUnidad = Array.isArray(newData.ventasPorUnidad.datos) ? newData.ventasPorUnidad.datos : [];
                        this.titulos.ventasPorUnidad = newData.ventasPorUnidad.titulo || this.titulos.ventasPorUnidad;
                    }
                    
                    if (newData.ventasPorTipoVenta) {
                        this.datos.ventasPorTipoVenta = Array.isArray(newData.ventasPorTipoVenta.datos) ? newData.ventasPorTipoVenta.datos : [];
                        this.titulos.ventasPorTipoVenta = newData.ventasPorTipoVenta.titulo || this.titulos.ventasPorTipoVenta;
                    }
                    
                    console.log('Datos actualizados:', this.datos);
                    console.log('Títulos actualizados:', this.titulos);
                },
                
                // Renderizar todos los gráficos
                renderAllCharts() {
                    this.renderImporteDiario();
                    this.renderTendenciaSemanal();
                    this.renderComparacionMensual();
                    this.renderRendimientoAnual();
                    this.renderDistribucionHoras();
                    this.renderVentasPorUnidad();
                    this.renderVentasPorTipoVenta();
                },
                
                // Renderizar gráfico de importes diarios
                renderImporteDiario() {
                    // Destruir gráfico existente si hay uno
                    if (this.charts.importeDiario) {
                        this.charts.importeDiario.destroy();
                        this.charts.importeDiario = null;
                    }
                    
                    const contenedor = document.getElementById('graficoImporteDiario');
                    if (!contenedor) return;
                    
                    // Limpiar el contenedor
                    contenedor.innerHTML = '';
                    
                    // Preparar datos
                    const datos = this.prepararDatos(this.datos.importeDiario);
                    
                    try {
            const options = {
                series: [{
                    name: 'Importe',
                                data: datos.values
                }],
                chart: {
                    type: 'area',
                                height: 350,
                                zoom: { enabled: true },
                                animations: { enabled: true }
                },
                title: {
                                text: this.titulos.importeDiario,
                    align: 'center',
                                style: { fontSize: '14px', fontWeight: 'bold' }
                            },
                            dataLabels: { enabled: false },
                            stroke: { curve: 'smooth', width: 2 },
                colors: ['#14b8a6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                                categories: datos.labels,
                                title: { text: 'Día del mes' }
                            },
                            yaxis: {
                                title: { text: 'Importe (S/.)' },
                                labels: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            }
                        };
                        
                        this.charts.importeDiario = new ApexCharts(contenedor, options);
                        this.charts.importeDiario.render();
                    } catch (error) {
                        console.error("Error al renderizar gráfico de importes diarios:", error);
                    }
                },
                
                // Renderizar gráfico de tendencia semanal
                renderTendenciaSemanal() {
                    // Destruir gráfico existente si hay uno
                    if (this.charts.tendenciaSemanal) {
                        this.charts.tendenciaSemanal.destroy();
                        this.charts.tendenciaSemanal = null;
                    }
                    
                    const contenedor = document.getElementById('graficoTendenciaSemanal');
                    if (!contenedor) return;
                    
                    // Limpiar el contenedor
                    contenedor.innerHTML = '';
                    
                    // Preparar datos
                    const datos = this.prepararDatos(this.datos.tendenciaSemanal);
                    
                    try {
                        const options = {
                            series: [{
                                name: 'Importe',
                                data: datos.values
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                zoom: { enabled: false },
                                animations: { enabled: true }
                            },
                            title: {
                                text: this.titulos.tendenciaSemanal,
                                align: 'center',
                                style: { fontSize: '14px', fontWeight: 'bold' }
                            },
                            dataLabels: { enabled: false },
                            colors: ['#0694a2'],
                            plotOptions: {
                                bar: {
                                    borderRadius: 5,
                                    columnWidth: '70%',
                                }
                            },
                            xaxis: {
                                categories: datos.labels,
                                title: { text: 'Semana' }
                            },
                            yaxis: {
                                title: { text: 'Importe (S/.)' },
                                labels: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            }
                        };
                        
                        this.charts.tendenciaSemanal = new ApexCharts(contenedor, options);
                        this.charts.tendenciaSemanal.render();
                    } catch (error) {
                        console.error("Error al renderizar gráfico de tendencia semanal:", error);
                    }
                },
                
                // Renderizar gráfico de comparación mensual
                renderComparacionMensual() {
                    // Destruir gráfico existente si hay uno
                    if (this.charts.comparacionMensual) {
                        this.charts.comparacionMensual.destroy();
                        this.charts.comparacionMensual = null;
                    }
                    
                    const contenedor = document.getElementById('graficoComparacionMensual');
                    if (!contenedor) return;
                    
                    // Limpiar el contenedor
                    contenedor.innerHTML = '';
                    
                    // Preparar datos
                    const datos = this.prepararDatos(this.datos.comparacionMensual);
                    
                    try {
                        const options = {
                            series: [{
                                name: 'Importe Total',
                                data: datos.values
                            }],
                            chart: {
                                type: 'line',
                                height: 350,
                                zoom: { enabled: false },
                                animations: { enabled: true }
                            },
                            title: {
                                text: this.titulos.comparacionMensual,
                                align: 'center',
                                style: { fontSize: '14px', fontWeight: 'bold' }
                            },
                            dataLabels: { enabled: false },
                            stroke: { curve: 'straight', width: 3 },
                            colors: ['#0e9f6e'],
                            markers: {
                                size: 6,
                                colors: ['#0e9f6e'],
                                strokeWidth: 2,
                                strokeColors: '#fff'
                            },
                            xaxis: {
                                categories: datos.labels,
                                title: { text: 'Mes' }
                            },
                            yaxis: {
                                title: { text: 'Importe Total (S/.)' },
                                labels: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            }
                        };
                        
                        this.charts.comparacionMensual = new ApexCharts(contenedor, options);
                        this.charts.comparacionMensual.render();
                    } catch (error) {
                        console.error("Error al renderizar gráfico de comparación mensual:", error);
                    }
                },
                
                // Renderizar gráfico de rendimiento anual
                renderRendimientoAnual() {
                    // Destruir gráfico existente si hay uno
                    if (this.charts.rendimientoAnual) {
                        this.charts.rendimientoAnual.destroy();
                        this.charts.rendimientoAnual = null;
                    }
                    
                    const contenedor = document.getElementById('graficoRendimientoAnual');
                    if (!contenedor) return;
                    
                    // Limpiar el contenedor
                    contenedor.innerHTML = '';
                    
                    // Preparar datos
                    const datos = this.prepararDatos(this.datos.rendimientoAnual);
                    
                    try {
                        const options = {
                            series: [{
                                name: 'Importe Total',
                                data: datos.values
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                zoom: { enabled: false },
                                animations: { enabled: true }
                            },
                            title: {
                                text: this.titulos.rendimientoAnual,
                                align: 'center',
                                style: { fontSize: '14px', fontWeight: 'bold' }
                            },
                            dataLabels: { enabled: false },
                            colors: ['#3f83f8'],
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    borderRadius: 4,
                                    columnWidth: '60%',
                                    distributed: true
                                }
                            },
                            xaxis: {
                                categories: datos.labels,
                                title: { text: 'Mes' }
                            },
                            yaxis: {
                                title: { text: 'Importe Total (S/.)' },
                                labels: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            }
                        };
                        
                        this.charts.rendimientoAnual = new ApexCharts(contenedor, options);
                        this.charts.rendimientoAnual.render();
                    } catch (error) {
                        console.error("Error al renderizar gráfico de rendimiento anual:", error);
                    }
                },
                
                // Renderizar gráfico de distribución de horas
                renderDistribucionHoras() {
                    // Destruir gráfico existente si hay uno
                    if (this.charts.distribucionHoras) {
                        this.charts.distribucionHoras.destroy();
                        this.charts.distribucionHoras = null;
                    }
                    
                    const contenedor = document.getElementById('graficoDistribucionHoras');
                    if (!contenedor) return;
                    
                    // Limpiar el contenedor
                    contenedor.innerHTML = '';
                    
                    // Preparar datos
                    const datos = this.prepararDatos(this.datos.distribucionHoras);
                    
                    try {
                        const options = {
                            series: datos.values,
                            chart: {
                                type: 'donut',
                                height: 350,
                                animations: { enabled: true }
                            },
                    title: {
                                text: this.titulos.distribucionHoras,
                                align: 'center',
                                style: { fontSize: '14px', fontWeight: 'bold' }
                            },
                            labels: datos.labels,
                            colors: ['#38bdf8', '#fb923c'], // Azul para mañana, Naranja para tarde
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '65%',
                                        labels: {
                                            show: true,
                                            total: {
                                                show: true,
                                                label: 'Total Horas',
                                                formatter: function(w) {
                                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(2) + ' horas';
                                                }
                                            }
                                        }
                                    },
                                    expandOnClick: true
                                }
                            },
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center'
                            },
                            dataLabels: { 
                                enabled: true,
                                formatter: function(val, opts) {
                                    const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    if (total === 0) return '0%';
                                    return Math.round(val * 100 / total) + '%';
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: val => val.toFixed(2) + ' horas'
                                }
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 320
                                    },
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }]
                        };
                        
                        this.charts.distribucionHoras = new ApexCharts(contenedor, options);
                        this.charts.distribucionHoras.render();
                    } catch (error) {
                        console.error("Error al renderizar gráfico de distribución de horas:", error);
                    }
                },
                
                // Renderizar gráfico de ventas por unidad
                renderVentasPorUnidad() {
                    // Destruir gráfico existente si hay uno
                    if (this.charts.ventasPorUnidad) {
                        this.charts.ventasPorUnidad.destroy();
                        this.charts.ventasPorUnidad = null;
                    }
                    
                    const contenedor = document.getElementById('graficoVentasPorUnidad');
                    if (!contenedor) return;
                    
                    // Limpiar el contenedor
                    contenedor.innerHTML = '';
                    
                    // Preparar datos
                    const datos = this.prepararDatos(this.datos.ventasPorUnidad);
                    
                    try {
                        const options = {
                            series: [{
                                name: 'Ventas',
                                data: datos.values
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                zoom: { enabled: false },
                                animations: { enabled: true },
                                toolbar: {
                                    show: true,
                                    tools: {
                                        download: true,
                                        selection: false,
                                        zoom: false,
                                        zoomin: false,
                                        zoomout: false,
                                        pan: false,
                                        reset: false
                                    }
                                }
                            },
                            title: {
                                text: this.titulos.ventasPorUnidad,
                                align: 'center',
                                style: { fontSize: '14px', fontWeight: 'bold' }
                            },
                            dataLabels: { 
                                enabled: true,
                                formatter: function(val) {
                                    return 'S/. ' + val.toFixed(2);
                                },
                                style: {
                                    fontSize: '12px',
                                    colors: ['#fff']
                                },
                                background: {
                                    enabled: true,
                                    foreColor: '#444',
                                    borderRadius: 2,
                                    padding: 4,
                                    opacity: 0.9,
                                    borderWidth: 1,
                                    borderColor: '#fff'
                                },
                                offsetX: 0,
                                offsetY: 0
                            },
                            colors: ['#047857'],  // Verde oscuro
                            plotOptions: {
                                bar: {
                                    horizontal: true,
                                    barHeight: '70%',
                                    borderRadius: 6,
                                    distributed: false,
                                    dataLabels: {
                                        position: 'top'
                                    }
                                }
                            },
                            grid: {
                                xaxis: {
                                    lines: {
                                        show: true
                                    }
                                },
                                yaxis: {
                                    lines: {
                                        show: false
                                    }
                                }
                            },
                            xaxis: {
                                categories: datos.labels,
                                title: { 
                                    text: 'Importe (S/.)',
                                    style: {
                                        fontSize: '12px',
                                        fontWeight: 600
                                    }
                                },
                                labels: {
                                    formatter: val => 'S/. ' + val.toFixed(2)
                    }
                },
                yaxis: {
                    title: {
                                    text: 'Unidad',
                                    style: {
                                        fontSize: '12px',
                                        fontWeight: 600
                                    }
                    },
                    labels: {
                                    style: {
                                        fontSize: '11px'
                                    }
                                }
                            },
                            tooltip: {
                                y: {
                                    title: {
                                        formatter: (seriesName) => 'Importe:'
                                    },
                                    formatter: val => 'S/. ' + val.toFixed(2)
                                }
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'dark',
                                    type: 'horizontal',
                                    shadeIntensity: 0.3,
                                    gradientToColors: ['#10b981'],  // Verde más claro
                                    inverseColors: false,
                                    opacityFrom: 1,
                                    opacityTo: 1
                                }
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        height: 400
                                    },
                                    plotOptions: {
                                        bar: {
                                            barHeight: '80%'
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false
                                    }
                                }
                            }]
                        };
                        
                        this.charts.ventasPorUnidad = new ApexCharts(contenedor, options);
                        this.charts.ventasPorUnidad.render();
                    } catch (error) {
                        console.error("Error al renderizar gráfico de ventas por unidad:", error);
                    }
                },
                
                // Renderizar gráfico de ventas por tipo de venta
                renderVentasPorTipoVenta() {
                    // Destruir gráfico existente si hay uno
                    if (this.charts.ventasPorTipoVenta) {
                        this.charts.ventasPorTipoVenta.destroy();
                        this.charts.ventasPorTipoVenta = null;
                    }
                    
                    const contenedor = document.getElementById('graficoVentasPorTipoVenta');
                    if (!contenedor) return;
                    
                    // Limpiar el contenedor
                    contenedor.innerHTML = '';
                    
                    // Preparar datos
                    const datos = this.prepararDatos(this.datos.ventasPorTipoVenta);
                    
                    try {
                        const options = {
                            series: datos.values,
                            chart: {
                                type: 'pie',
                                height: 350,
                                animations: { enabled: true },
                                toolbar: {
                                    show: true,
                                    tools: {
                                        download: true,
                                        selection: false,
                                        zoom: false,
                                        zoomin: false,
                                        zoomout: false,
                                        pan: false,
                                        reset: false
                                    }
                                }
                            },
                            title: {
                                text: this.titulos.ventasPorTipoVenta,
                                align: 'center',
                                style: { fontSize: '14px', fontWeight: 'bold' }
                            },
                            labels: datos.labels,
                            colors: ['#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#0891b2'],
                            plotOptions: {
                                pie: {
                                    expandOnClick: true,
                                    donut: {
                                        size: '0%'
                                    }
                                }
                            },
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center',
                                fontSize: '12px'
                            },
                            dataLabels: { 
                                enabled: true,
                                formatter: function(val, opts) {
                                    const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return Math.round(val * 100 / total) + '%';
                                },
                                style: {
                                    fontSize: '12px',
                                    fontWeight: 'bold',
                                    colors: ['#fff']
                                },
                                dropShadow: {
                                    enabled: true,
                                    color: '#000',
                                    top: 1,
                                    left: 1,
                                    blur: 2,
                                    opacity: 0.3
                    }
                },
                tooltip: {
                    y: {
                                    formatter: function(val) {
                            return 'S/. ' + val.toFixed(2);
                        }
                                }
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        height: 300
                                    },
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }]
                        };
                        
                        this.charts.ventasPorTipoVenta = new ApexCharts(contenedor, options);
                        this.charts.ventasPorTipoVenta.render();
            } catch (error) {
                        console.error("Error al renderizar gráfico de ventas por tipo de venta:", error);
                    }
                },
                
                // Función auxiliar para preparar los datos
                prepararDatos(datosArray) {
                    // Asegurarse de que sea un array
                    const array = Array.isArray(datosArray) ? datosArray : [];
                    
                    // Extraer valores y etiquetas
                    const values = array.map(item => (item && typeof item.y !== 'undefined') ? Number(item.y) : 0);
                    const labels = array.map(item => (item && typeof item.x !== 'undefined') ? item.x : '');
                    
                    return { values, labels };
                }
            }));
        });
    </script>
    @endpush
</div>
