

// Initialize Swiper for building carousel
var swiper = new Swiper('.swiper-container', {
    loop: true, // Infinite loop
    autoplay: {
        delay: 3000, // Delay between slides (in ms)
        disableOnInteraction: false, // Keep autoplay active even after user interaction
    },
    navigation: {
        nextEl: '.swiper-button-next', // Next button clasiis
        prevEl: '.swiper-button-prev', // Previous button class
    },
    pagination: {
        el: '.swiper-pagination', // Pagination class
        clickable: true, // Allows pagination bullets to be clicked
    },
    effect: 'fade', // Fade effect for smooth transitions
    speed: 1000, // Transition speed (in ms)
});
