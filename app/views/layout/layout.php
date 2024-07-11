
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'My Application'; ?></title>
    <!-- Fonts -->

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css" type="text/css">

</head>

<body>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3 sidebar" id="profil">
                <div data-aos="fade-up">
                    <div class="text-center mb-3">
                        <div class="bg-white rounded-circle mx-auto imgprof"  ><img src="assets/img/clients/profile.jpeg" class="img-fluid rounded-circle" alt=""></div>
                    </div>
                    <h5 class="text-center">Ilham Nur Isnaini Baskara Jaya</h5>
                    <div class="text-center mb-3 ">
                        <span class="badge badge-light p-2 mt-3">Web - Developer</span>
                        <span class="badge badge-light p-2 mt-3">Mobile - Developer</span>
                    </div>
                    <div class="contact-info">
                        <div class="row">
                            <div class="col-sm  m-0">
                                <div class="d-flex">
                                    <div class="d-flex align-items-center">
                                        <div class="ico m-0 ">
                                            <i class="fa-solid fa-at fa-lg" style="color: #1d2850;"></i>
                                        </div>
                                    </div>
                                    <div class="ml-2 content-info">
                                        <p class="title">Email</p>
                                        <p class="sub-title">Kzkzaj@gmail.com</p>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="d-flex align-items-center">
                                        <div class="ico mr-2"><i class="fa-solid fa-phone fa-lg" style="color: #1d2850;"></i></i></div>
                                        <div class="content-info">
                                            <p class="title">Phone</p>
                                            <p class="sub-title">089539777935</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm m-0">
                                <div class="d-flex">
                                    <div class="d-flex align-items-center">
                                        <div class="ico mr-2" style="padding-top:4px;padding-left:7px;"><i class="fa-solid fa-cake-candles fa-lg" style="color: #1d2850;"></i></div>
                                        <div class="content-info">
                                            <p class="title">Birth Of Date</p>
                                            <p class="sub-title">01-07-2002</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="d-flex align-items-center">
                                        <div class="ico mr-2" style="padding-top:4px;padding-left:8px;"><i class="fa-solid fa-location-dot fa-bounce fa-lg" style="color: #1d2850;"></i></i></div>
                                        <div class="content-info">
                                            <p class="title">Location</p>
                                            <p class="sub-title">Bondowoso,JawaTimur</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </div>
                    <div class="text-center mt-3">
                        <span class="badge badge-light"><i class="fa-brands fa-linkedin"></i></span>
                        <span class="badge badge-light"><i class="fa-brands fa-github"></i></span>
                        <span class="badge badge-light"><i class="fa-brands fa-square-instagram"></i></span>
                    </div>
                </div>
            </div>
       
            <div class="col-md-9">
                <div class="main-content">
                    <div class="d-flex justify-content-end navv">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="/">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="resume">Resume</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link   " href="portfolio">Portfolio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="contact">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="sub-content">
                    <?php echo \Core\View::yield('content'); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="preloader"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>

    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <!-- Include Isotope Library -->
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    <script src="https://kit.fontawesome.com/4f7845fbe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            "loop": true,
            "speed": 600,
            "centeredSlides": true,
            "autoplay": {
                "delay": 5000
            },
            "slidesPerView": "auto",
            "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
            },
            "breakpoints": {
                "320": {
                    "slidesPerView": 3,
                    "spaceBetween": 20
                },
                "580": {
                    "slidesPerView": 3,
                    "spaceBetween": 20
                },
                "767": {
                    "slidesPerView": 4,
                    "spaceBetween": 20
                },
                "992": {
                    "slidesPerView": 4,
                    "spaceBetween": 20
                },
                "1200": {
                    "slidesPerView": 4,
                    "spaceBetween": 20
                }
            }
        });
    </script>
    <script type="text/javascript">
        const lightbox = GLightbox({
            selector: '.glightbox'
        });
    </script>
    <script src="main.js"></script>
</body>

</html>