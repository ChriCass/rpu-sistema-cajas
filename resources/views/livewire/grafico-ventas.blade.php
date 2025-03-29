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

        <!-- Contenedor principal de gráficos -->
        <div x-data="ventasCharts(@js($datosGrafico))" x-init="initCharts()" class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    rendimientoAnual: null
                },
                
                // Datos para los gráficos
                datos: {
                    importeDiario: inicialData?.importeDiario?.datos || [],
                    tendenciaSemanal: inicialData?.tendenciaSemanal?.datos || [],
                    comparacionMensual: inicialData?.comparacionMensual?.datos || [],
                    rendimientoAnual: inicialData?.rendimientoAnual?.datos || []
                },
                
                // Títulos para los gráficos
                titulos: {
                    importeDiario: inicialData?.importeDiario?.titulo || 'Importes Diarios',
                    tendenciaSemanal: inicialData?.tendenciaSemanal?.titulo || 'Tendencia Semanal',
                    comparacionMensual: inicialData?.comparacionMensual?.titulo || 'Comparación Mensual',
                    rendimientoAnual: inicialData?.rendimientoAnual?.titulo || 'Rendimiento Anual'
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
                    
                    console.log('Datos actualizados:', this.datos);
                    console.log('Títulos actualizados:', this.titulos);
                },
                
                // Renderizar todos los gráficos
                renderAllCharts() {
                    this.renderImporteDiario();
                    this.renderTendenciaSemanal();
                    this.renderComparacionMensual();
                    this.renderRendimientoAnual();
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
