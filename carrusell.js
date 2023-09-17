$(document).ready(function(){
    $('.project-carousel').slick({
        slidesToShow: 3, // Número de proyectos a mostrar en un momento dado
        slidesToScroll: 1, // Número de proyectos a desplazarse a la vez
        autoplay: true, // Activar reproducción automática
        autoplaySpeed: 900, // Duración de cada diapositiva en milisegundos
        responsive: [
            {
                breakpoint: 768, // Cambia el número de proyectos a mostrar en resoluciones más pequeñas
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480, // Cambia el número de proyectos a mostrar en resoluciones aún más pequeñas
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    });
});

