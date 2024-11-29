<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body class="bg-light">


    <!-- Contact Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4 fw-bold h-font" style="color:#C80F2F" ;>Contact Us</h2>

        <div class="row">


            <!-- Google Map -->

            <div class="col-lg-6 col-md-12">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12208.31901099453!2d7.9927659123180215!3d58.161093380083216!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4638025378c67fc7%3A0xfd4fe654e2fbbb6a!2sUniversity%20of%20Agder!5e0!3m2!1sen!2sno!4v1732396148976!5m2!1sen!2sno"
                    width="100%"
                    height="400"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="bg-white p-4 rounded shadow">
                    <h5 class="fw-bold" style="color:#C80F2F;">Address</h5>
                    <p><i class="bi bi-geo-alt"></i> Campus Kristiansand, Universitetsveien 25, 4630 Kristiansand, Norge</p>

                    <h5 class="fw-bold">Phone</h5>
                    <p><a href="tel:+1234567890" class="text-decoration-none text-dark"><i class="bi bi-telephone"></i> +123 456 7890</a></p>

                    <!-- Textarea for message -->
                  
                    <h5 class="fw-bold mt-4" style="color:#C80F2F;">Leave a Message</h5>
                    <form method="POST" action="/uia_motell/inc/contact_form.php">


                        <div class="form-group">
                            <textarea name="message" rows="5" class="form-control shadow-none" placeholder="Type your message here..." required></textarea>
                        </div>
                        <button type="submit" class="btn mt-3 text-white" style="background-color: #C80F2F;">Send</button>
                    </form>


                </div>
            </div>


        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>