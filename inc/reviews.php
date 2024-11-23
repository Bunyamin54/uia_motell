<h2 class="mt-5 pl-4 mb-4 text-center fw-bold h-font" style="color:#C80F2F;">Customer Reviews</h2>

<div class="container">

    <!-- Swiper for Reviews -->
    <div class="swiper swiper-reviews">
        <div class="swiper-wrapper">
            <!-- Review 1 -->
            <div class="swiper-slide bg-white p-3 rounded shadow-sm" style="max-width: 300px; margin-right: 15px;">
                <div class="profile d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle" style="font-size: 30px; "></i>
                    <h6 class="m-0 ms-2">Elizabet Rasmussen</h6>
                </div>
                <p class="small">
                    "Absolutely loved the ambiance and the staff were super friendly! Highly recommend for a peaceful getaway."
                </p>
                <div class="reviews-rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-half text-warning"></i>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="swiper-slide bg-white p-3 rounded shadow-sm" style="max-width: 300px; margin-right: 15px;">
                <div class="profile d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle" style="font-size: 30px; "></i>
                    <h6 class="m-0 ms-2">Håkon Smith</h6>
                </div>
                <p class="small">
                    "Great value for money! The rooms were clean and well-maintained. Would definitely visit again."
                </p>
                <div class="reviews-rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="swiper-slide bg-white p-3 rounded shadow-sm" style="max-width: 300px; margin-right: 15px;">
                <div class="profile d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle" style="font-size: 30px; "></i>
                    <h6 class="m-0 ms-2">Emily Nilsen</h6>
                </div>
                <p class="small">
                    "The facilities exceeded my expectations. A perfect place for a family vacation!"
                </p>
                <div class="reviews-rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                </div>
            </div>
            <!-- Review 4 -->
            <div class="swiper-slide bg-white p-3 rounded shadow-sm" style="max-width: 300px; margin-right: 15px;">
                <div class="profile d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle" style="font-size: 30px; "></i>
                    <h6 class="m-0 ms-2">Nora Amundsen</h6>
                </div>
                <p class="small">
                    "A perfect place for a family vacation! And the staff were super friendly! Highly recommend."
                </p>
                <div class="reviews-rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-half text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">


<!-- Swiper Pagination -->
<div class="swiper-pagination mt-3"></div>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>





<script>
    const swiper = new Swiper('.swiper-reviews', {
        loop: true,
        slidesPerView: 3, // Show 3 cards at a time
        spaceBetween: 15, // Margin between cards
        autoplay: {
            delay: 1, // Minimal delay to allow smooth scrolling
            disableOnInteraction: false,
        },
        speed: 5000, // Continuous scrolling speed
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
</script>

