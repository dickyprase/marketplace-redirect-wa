<?php
include "header.php"
?>

<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero section">

    <div class="container-fluid p-0" data-aos="fade">

      <div class="hero-slider swiper init-swiper">
        <script type="application/json" class="swiper-config">
          {
            "loop": true,
            "speed": 1000,
            "effect": "fade",
            "autoplay": {
              "delay": 5000
            },
            "slidesPerView": 1,
            "navigation": {
              "nextEl": ".swiper-button-next",
              "prevEl": ".swiper-button-prev"
            }
          }
        </script>

        <div class="swiper-wrapper">

          <div class="swiper-slide">
            <div class="hero-item">
              <img src="assets/img/hero/hero1.webp" alt="Hero Image" class="img-fluid">
            </div>
          </div><!-- End slide item -->

          <div class="swiper-slide">
            <div class="hero-item">
              <img src="assets/img/hero/hero2.webp" alt="Hero Image" class="img-fluid">

            </div>
          </div><!-- End slide item -->

          <div class="swiper-slide">
            <div class="hero-item">
              <img src="assets/img/hero/hero3.webp" alt="Hero Image" class="img-fluid">

            </div>
          </div><!-- End slide item -->

        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

      </div>

    </div>

  </section><!-- /Hero Section -->

  <!-- category Posts Section -->
  <section id="category-posts" class="category-posts section">

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="category-posts-slider swiper init-swiper">
        <script type="application/json" class="swiper-config">
          {
            "loop": true,
            "speed": 600,
            "autoplay": {
              "delay": 1500
            },
            "slidesPerView": 1,
            "spaceBetween": 40,
            "centeredSlides": false,
            "breakpoints": {
              "768": {
                "slidesPerView": 1.5,
                "spaceBetween": 30
              },
              "1200": {
                "slidesPerView": 3,
                "spaceBetween": 40
              }
            },
            "pagination": {
              "el": ".swiper-pagination",
              "clickable": true
            }
          }
        </script>

        <div class="swiper-wrapper">

          <div class="swiper-slide">
            <article class="category-card">
              <div class="category-image">
                <img src="assets/img/product/dress1.webp" alt="Blog Image" loading="lazy">
                <div class="category-badge">Dress</div>
              </div>
              <div class="category-content">
                <a href="category-details.php" class="btn btn-dark">
                  <span>Lihat Lebih Banyak</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </article>
          </div><!-- End slide item -->

          <div class="swiper-slide">
            <article class="category-card">
              <div class="category-image">
                <img src="assets/img/product/dress2.webp" alt="Blog Image" loading="lazy">
                <div class="category-badge">Top</div>
              </div>
              <div class="category-content">
                <a href="category-details.php" class="btn btn-dark">
                  <span>Lihat Lebih Banyak</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </article>
          </div><!-- End slide item -->

          <div class="swiper-slide">
            <article class="category-card">
              <div class="category-image">
                <img src="assets/img/product/dress3.webp" alt="Blog Image" loading="lazy">
                <div class="category-badge">Bottom</div>
              </div>
              <div class="category-content">
                <a href="category-details.php" class="btn btn-dark">
                  <span>Lihat Lebih Banyak</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </article>
          </div><!-- End slide item -->

          <div class="swiper-slide">
            <article class="category-card">
              <div class="category-image">
                <img src="assets/img/product/dress4.webp" alt="Blog Image" loading="lazy">
                <div class="category-badge">Outer</div>
              </div>
              <div class="category-content">
                <a href="category-details.php" class="btn btn-dark">
                  <span>Lihat Lebih Banyak</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </article>
          </div><!-- End slide item -->

          <div class="swiper-slide">
            <article class="category-card">
              <div class="category-image">
                <img src="assets/img/product/dress5.webp" alt="Blog Image" loading="lazy">
                <div class="category-badge">Bag</div>
              </div>
              <div class="category-content">
                <a href="category-details.php" class="btn btn-dark">
                  <span>Lihat Lebih Banyak</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </article>
          </div><!-- End slide item -->

        </div>

        <div class="swiper-pagination"></div>
      </div>

    </div>

  </section><!-- /category Posts Section -->

  <!-- Product Posts Section -->
  <section id="product-posts" class="product-posts section">

    <!-- Section Title -->
    <div class="container section-title product-category-scroll" data-aos="fade-up">
      <button class="btn btn-product-post-category me-2 rounded-0 active">New Arrivals</button>
      <button class="btn btn-product-post-category me-2 rounded-0">Best Seller</button>
      <button class="btn btn-product-post-category me-2 rounded-0">Big Sale</button>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="row gy-4 align-items-stretch">

        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
          <article class="card-post h-100">
            <div class="post-img position-relative overflow-hidden">
              <img src="assets/img/product/dress1.webp" class="img-fluid w-100" alt="Post image" loading="lazy">
            </div>
            <div class="content">
              <div class="meta d-flex align-items-center flex-wrap gap-2">
                <span class="cat-badge">New Arrivals</span>
                <span class="cat-badge">Best Seller</span>
              </div>
              <h3 class="title">Cole</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
              <h5>Rp. 999.999</h5>
              <div class="mt-3 text-center">
                <a href="detail-product.php" class="btn btn-dark ps-5 pe-5">Detail</a>
              </div>
            </div>
          </article>
        </div><!-- End Grid Post -->

      </div>

    </div>

  </section><!-- /Sale Posts Section -->

</main>

<?php
include "footer.php";
?>