    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Portfolio</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="isotope-layout" data-default-filter="*" data-layout="fitRows" data-sort="original-order">

          <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
            <li data-filter="*" class="filter-active">All</li>
            <li data-filter=".web-app">Web App</li>
            <li data-filter=".mobile-app">Mobile App</li>
            <li data-filter=".desktop-app">Desktop App</li>
            <li data-filter=".iot">Iot</li>
            <li data-filter=".other">Other</li>

          </ul><!-- End Portfolio Filters -->

          <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
          <?php foreach($datas as $data):?>
            <div class='col-lg-4 col-md-6 mt-4 portfolio-item isotope-item <?= $data['kategori_project'] ?>'>
              <a href="">
              <img src='uploads/<?= $data['nama_file']?>' class="img-fluid" alt="">

              </a>
              <div class="portfolio-info">
                <h4><?=$data['nama_project'] ?></h4>
                <p><?=$data['deksripsi_project'] ?></p>
                <a href='uploads/<?= $data['nama_file']?>' title="<?=$data['nama_project'] ?>" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
              </div>
            </div><!-- End Portfolio Item -->
            <?php endforeach;?>

          </div><!-- End Portfolio Container -->

        </div>

      </div>

    </section><!-- /Portfolio Section -->