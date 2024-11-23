<div class="container-fluid px-lg-4 mt-4">
    <!-- Swiper Section -->
    <div class="swiper swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="../public/images/home/1.jpeg" class="w-100 d-block" style="height: 575px; object-fit: cover;" />
            </div>
            <div class="swiper-slide">
                <img src="../public/images/home/2.jpeg" class="w-100 d-block" style="height: 575px; object-fit: cover;" />
            </div>
            <div class="swiper-slide">
                <img src="../public/images/home/3.jpeg" class="w-100 d-block" style="height: 575px; object-fit: cover;" />
            </div>
            <div class="swiper-slide">
                <img src="../public/images/home/4.jpeg" class="w-100 d-block" style="height: 575px; object-fit: cover;" />
            </div>
            <div class="swiper-slide">
                <img src="../public/images/home/5.jpg" class="w-100 d-block" style="height: 575px; object-fit: cover;" />
            </div>
            <div class="swiper-slide">
                <img src="../public/images/home/6.jpg" class="w-100 d-block" style="height: 575px; object-fit: cover;" />
            </div>
            <div class="swiper-slide">
                <img src="../public/images/home/1.jpeg" class="w-100 d-block" style="height: 575px; object-fit: cover;" />
            </div>
        </div>
      
        <div class="swiper-pagination"></div>
    </div>
</div>

<!-- Availability Form Section -->
<div class="container availability-form mt-4">
    <div class="row">
        <div class="col-lg-12 bg-white shadow p-4 rounded">
            <h5 class="mb-4">Check Room Availability for Booking</h5>
            <form>
                <div class="row align-items-end">
                    <div class="col-lg-3 mb-3">
                        <label for="checkInDatePicker" class="form-label" style="font-weight:500;">Check-In</label>
                        <input id="checkInDatePicker" type="text" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label for="checkOutDatePicker" class="form-label" style="font-weight:500;">Check-Out</label>
                        <input id="checkOutDatePicker" type="text" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight:500;">Adults</label>
                        <select class="form-select shadow-none">
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label class="form-label" style="font-weight:500;">Children</label>
                        <select class="form-select shadow-none">
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-1 mb-lg-3 mt-2">
                        <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<!-- Custom JS -->
<script src="../public/scripts.js"></script>
