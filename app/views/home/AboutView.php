<?php \Core\View::extends('layout.layout'); ?>

<?php \Core\View::startSection('content'); ?>
<div class="container section-title" data-aos="fade-up">
    <h2>About Me</h2>
    <p>Hi there! I'm Illham Nur Isnaini Baskara Jaya, a passionate and experienced web dev and mobile dev. I specialize in backend developer and mobile developer and I thrive on creating innovative solutions that drive success.</p>

    <div class="mt-3">
        <h5>What I'm Doing</h5>
        <div class="row">
            <div class="col-md-6" >
                <div class="bg-white text-dark p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-sm-2">
                            <i class="bi bi-phone" style="font-size: 2rem; margin-right: 15px;"></i>
                        </div>

                        <div class="col-sm-10">
                            <h6>Mobile Apps</h6>
                            <p>I design and develop mobile applications for Android platforms, ensuring high performance and a seamless user experience.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white text-dark p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-sm-2">
                            <i class="bi bi-laptop" style="font-size: 2rem; margin-right: 15px;"></i>
                        </div>

                        <div class="col-sm-10">
                            <h6>Web Development</h6>
                            <p>From front-end interfaces to robust back-end systems, I create dynamic and responsive websites that meet clients' needs.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="bg-white text-dark p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-sm-2"> <i class="bi bi-lightbulb" style="font-size: 2rem; margin-right: 15px;"></i></div>

                        <div class="col-sm-10">
                            <h6>Learning AI</h6>
                            <p>I am currently learning about artificial intelligence, focusing on machine learning and data analytics to develop intelligent solutions in the future.</p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white text-dark p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-sm-2">
                            <i class="bi bi-cloud" style="font-size: 2rem; margin-right: 15px;"></i>
                        </div>
                        <div class="col-sm-10">
                            <h6>Exploring IoT</h6>
                            <p>I am exploring the IoT understand how interconnected devices can enhance efficiency and convenience through smart interactions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="mt-3">
        <h5>Client</h5>
        <!-- Slider main container -->
        <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide" ><img src="assets/img/clients/wijaya.png"class="img-fluid small-img" alt=""></div>
            <!-- <div class="swiper-slide"><img src="assets/img/clients/client-2.png" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="assets/img/clients/client-3.png" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="assets/img/clients/client-4.png" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="assets/img/clients/client-5.png" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="assets/img/clients/client-6.png" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="assets/img/clients/client-7.png" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="assets/img/clients/client-8.png" class="img-fluid" alt=""></div> -->
          </div>

        </div>


    </div>

</div>
<?php \Core\View::stopSection(); ?>