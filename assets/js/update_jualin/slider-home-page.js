$(document).ready(function () {

    $('.featured-categories-collection').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows:true,
        dots: true,
        pauseOnHover: true,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 700,
            settings: {
                slidesToShow: 3
            }
        }]
    });


});