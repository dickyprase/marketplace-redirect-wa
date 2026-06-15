<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>MarketPlace</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- <img src="assets/img/logo.webp" alt=""> -->
        <h1 class="sitename">MarketPlace</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Beranda</a></li>
          <li class="dropdown"><a href="#"><span>Promo</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="category-details.php">New Arrivals</a></li>
              <li><a href="category-details.php">Best Seller</a></li>
              <li><a href="category-details.php">Big Sale</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Kategori</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="category-details.php">Atasan</a></li>
              <li><a href="category-details.php">Kaos</a></li>
              <li><a href="category-details.php">Bawahan</a></li>
            </ul>
          </li>
          <li><a href="contact.php">Kontak Kami</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="header-social-links">
        <a href="#" class="social-media threads me-2"><i class="bi bi-threads"></i></a>
        <a href="#" class="social-media facebook me-2"><i class="bi bi-facebook"></i></a>
        <a href="#" class="social-media instagram me-2"><i class="bi bi-instagram"></i></a>
        <a class="cart me-2" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button" aria-controls="offcanvasCart">
          <i class="bi bi-cart"></i>
        </a>

        <div class="container offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
          <div class="offcanvas-header">
            <h3 class="offcanvas-title" id="offcanvasCartLabel">Keranjang</h3>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <hr>
          <div class="offcanvas-body">
            <div class="cart-items">
              <div class="row">
                <div class="col-4">
                  <img src="assets/img/product/dress1.webp" alt="">
                </div>
                <div class="col-8">
                  <h5>Test</h5>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus, aliquam.</p>
                  <p><strong>Size : </strong>XL</p>
                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <div class="input-group input-group-sm" style="width: 90px;">
                        <button class="btn btn-outline-secondary rounded-0" type="button">-</button>
                        <input type="text" class="form-control text-center" value="1">
                        <button class="btn btn-outline-secondary rounded-0" type="button">+</button>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 text-end mt-2 mt-sm-0">
                      <div class="ms-3">
                        <span class="fs-5">Rp 55.900</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>
            <div class="cart-items">
              <div class="row">
                <div class="col-4">
                  <img src="assets/img/product/dress1.webp" alt="">
                </div>
                <div class="col-8">
                  <h5>Test</h5>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus, aliquam.</p>
                  <p><strong>Size : </strong>XL</p>
                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <div class="input-group input-group-sm" style="width: 90px;">
                        <button class="btn btn-outline-secondary rounded-0" type="button">-</button>
                        <input type="text" class="form-control text-center" value="1">
                        <button class="btn btn-outline-secondary rounded-0" type="button">+</button>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 text-end mt-2 mt-sm-0">
                      <div class="ms-3">
                        <span class="fs-5">Rp 55.900</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>
            <div class="cart-items">
              <div class="row">
                <div class="col-4">
                  <img src="assets/img/product/dress1.webp" alt="">
                </div>
                <div class="col-8">
                  <h5>Test</h5>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus, aliquam.</p>
                  <p><strong>Size : </strong>XL</p>
                  <div class="row">
                    <div class="col-12 col-sm-6">
                      <div class="input-group input-group-sm" style="width: 90px;">
                        <button class="btn btn-outline-secondary rounded-0" type="button">-</button>
                        <input type="text" class="form-control text-center" value="1">
                        <button class="btn btn-outline-secondary rounded-0" type="button">+</button>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 text-end mt-2 mt-sm-0">
                      <div class="ms-3">
                        <span class="fs-5">Rp 55.900</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>
          </div>
          <div class="offcanvas-footer">
            <hr>
            <!-- Footer -->
            <div class="cart-footer mb-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span>SUBTOTAL</span>
                <strong class="fs-4">Rp 169.805</strong>
              </div>
              <button class="btn btn-dark w-100 rounded-0 mb-2">
                CHECKOUT
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </header>