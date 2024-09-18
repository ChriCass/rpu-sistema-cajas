Livewire.on('scroll-up', () => {
    // Hacer scroll hacia la parte superior de la página
    window.scrollTo({
        top: 0,
        behavior: 'smooth'  // Hace que el scroll sea suave
    });
});

 
 
    window.addEventListener('scroll-down', () => {
        setTimeout(() => {
            // Scroll a una posición específica (por ejemplo, 500 píxeles desde la parte superior)
            window.scrollTo({
                top: 1350,  // Cambia esto al valor que desees
                behavior: 'smooth'
            });
        }, 900);  // Esperar 2 segundos antes de hacer scroll
    });
 

import './bootstrap';
