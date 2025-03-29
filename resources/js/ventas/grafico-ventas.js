Livewire.on('datosActualizados', (data) => {
    console.log('Datos actualizados recibidos:', data);
    console.log('Año:', data.anio);
    console.log('Mes:', data.mes);
}); 