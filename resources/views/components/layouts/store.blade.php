<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? \App\Models\Setting::get('site_name', config('app.name')) }}</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>
<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1 class="sitename">MarketPlace</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
          <li class="dropdown"><a href="#"><span>Promo</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              @foreach(\App\Models\Tag::all() as $tag)
                <li><a href="{{ route('home') }}#tag-{{ $tag->slug }}">{{ $tag->name }}</a></li>
              @endforeach
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Kategori</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              @foreach(\App\Models\Category::all() as $cat)
                <li><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>
              @endforeach
            </ul>
          </li>
          <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak Kami</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <div class="header-social-links">
        <a href="#" class="social-media threads me-2"><i class="bi bi-threads"></i></a>
        <a href="#" class="social-media facebook me-2"><i class="bi bi-facebook"></i></a>
        <a href="#" class="social-media instagram me-2"><i class="bi bi-instagram"></i></a>
        <a class="cart me-2" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button" aria-controls="offcanvasCart">
          <i class="bi bi-cart"></i>
          <span class="badge bg-danger rounded-pill" id="cart-count-badge" style="display:none">0</span>
        </a>

        <div class="container offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
          <div class="offcanvas-header">
            <h3 class="offcanvas-title" id="offcanvasCartLabel">Keranjang</h3>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <hr>
          <div class="offcanvas-body" id="cart-items-container">
            <p class="text-center text-muted" id="cart-empty-msg">Keranjang kosong</p>
          </div>
          <div class="offcanvas-footer">
            <hr>
            <div class="cart-footer mb-3 px-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span>SUBTOTAL</span>
                <strong class="fs-4" id="cart-subtotal">Rp 0</strong>
              </div>
              <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="customer_name" id="checkout-customer-name" value="">
                <input type="hidden" name="address" id="checkout-address" value="">
                <input type="hidden" name="notes" id="checkout-notes" value="">
                <button type="button" class="btn btn-dark w-100 rounded-0 mb-2" id="btn-checkout-cart">
                  CHECKOUT
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  @if (session('error'))
    <div class="container mt-3">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif
  @if (session('success'))
    <div class="container mt-3">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif

  <!-- Modal Checkout Keranjang -->
  <div class="modal fade" id="cartCheckoutModal" tabindex="-1" aria-labelledby="cartCheckoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cartCheckoutModalLabel">Checkout Keranjang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="cartCheckoutForm" method="POST" action="{{ route('checkout.process') }}">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="cart-checkout-name" class="form-label">Nama Pembeli <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="cart-checkout-name" name="customer_name" required placeholder="Nama lengkap Anda">
            </div>
            <div class="mb-3">
              <label for="cart-checkout-address" class="form-label">Alamat <span class="text-danger">*</span></label>
              <textarea class="form-control" id="cart-checkout-address" name="address" rows="2" required placeholder="Alamat lengkap pengiriman"></textarea>
            </div>
            <div class="mb-3">
              <label for="cart-checkout-notes" class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
              <textarea class="form-control" id="cart-checkout-notes" name="notes" rows="2" placeholder="Permintaan khusus"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-dark">
              <i class="bi bi-whatsapp me-1"></i> Checkout via WhatsApp
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <main class="main">
    {{ $slot }}
  </main>

  <footer id="footer" class="footer position-relative light-background">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-4">
          <div class="footer-brand">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center mb-3">
              <span class="sitename">MarketPlace</span>
            </a>
            <p class="tagline">Inovasi market digital dengan solusi elegan.</p>
            <div class="social-links mt-4">
              <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
              <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
              <a href="#" aria-label="Threads"><i class="bi bi-threads"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="footer-links-grid">
            <div class="row">
              <div class="col-6 col-md-3">
                <h5>Promo</h5>
                <ul class="list-unstyled">
                  @foreach(\App\Models\Tag::all() as $tag)
                    <li><a href="{{ route('home') }}#tag-{{ $tag->slug }}">{{ $tag->name }}</a></li>
                  @endforeach
                </ul>
              </div>
              <div class="col-6 col-md-3">
                <h5>Kategori</h5>
                <ul class="list-unstyled">
                  @foreach(\App\Models\Category::all() as $cat)
                    <li><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                  @endforeach
                </ul>
              </div>
              <div class="col-6 col-md-3">
                <h5>Marketplace</h5>
                <ul class="list-unstyled">
                  <li><a href="#">Shopee</a></li>
                  <li><a href="#">Tokopedia</a></li>
                </ul>
              </div>
              <div class="col-6 col-md-3">
                <h5>Bantuan</h5>
                <ul class="list-unstyled">
                  <li><a href="{{ route('contact') }}">Kontak Kami</a></li>
                  <li><a href="#">Privacy Policy</a></li>
                  <li><a href="#">Terms of Service</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="footer-bottom-content">
              <p class="mb-0">&copy; <strong>MarketPlace</strong>. All rights reserved.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <script>
    (function() {
      const CSRF = document.querySelector('meta[name="csrf-token"]').content;

      function money(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
      }

      function fetchCart() {
        fetch('{{ route("cart.index") }}', { headers: { 'Accept': 'application/json' } })
          .then(r => r.json())
          .then(renderCart)
          .catch(() => {});
      }

      function renderCart(data) {
        const box = document.getElementById('cart-items-container');
        const badge = document.getElementById('cart-count-badge');
        const subtotalEl = document.getElementById('cart-subtotal');
        const emptyMsg = document.getElementById('cart-empty-msg');

        if (!data.items || data.items.length === 0) {
          if (emptyMsg) emptyMsg.style.display = 'block';
          if (badge) badge.style.display = 'none';
          if (subtotalEl) subtotalEl.textContent = 'Rp 0';
          box.innerHTML = '<p class="text-center text-muted" id="cart-empty-msg">Keranjang kosong</p>';
          return;
        }

        if (emptyMsg) emptyMsg.style.display = 'none';
        badge.style.display = 'inline';
        badge.textContent = data.items.length;
        subtotalEl.textContent = money(data.subtotal);

        let html = '';
        data.items.forEach((item, idx) => {
          html += '<div class="cart-items"><div class="row align-items-center">';
          html += '<div class="col-4"><img src="' + (item.image || '{{ asset("assets/img/product/dress1.webp") }}') + '" alt="" class="img-fluid rounded"></div>';
          html += '<div class="col-8">';
          html += '<h5>' + item.name + '</h5>';
          if (item.size_label) html += '<p><strong>Size: </strong>' + item.size_label + '</p>';
          html += '<div class="row align-items-center">';
          html += '<div class="col-6"><div class="input-group input-group-sm" style="width:90px">';
          html += '<button class="btn btn-outline-secondary rounded-0" onclick="cartUpdate(' + idx + ',' + (item.qty - 1) + ')">-</button>';
          html += '<input type="text" class="form-control text-center" value="' + item.qty + '" readonly>';
          html += '<button class="btn btn-outline-secondary rounded-0" onclick="cartUpdate(' + idx + ',' + (item.qty + 1) + ')">+</button>';
          html += '</div></div>';
          html += '<div class="col-6 text-end"><span class="fs-6">' + money(item.subtotal) + '</span></div>';
          html += '</div></div></div><hr></div>';
        });
        box.innerHTML = html;
      }

      window.addToCart = function(productId, sizeId, qty) {
        fetch('{{ route("cart.add") }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
          body: JSON.stringify({ product_id: productId, size_id: sizeId, qty: qty || 1 })
        }).then(r => r.json()).then(renderCart).catch(() => {});
      };

      window.cartUpdate = function(index, qty) {
        fetch('/cart/update/' + index, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
          body: JSON.stringify({ qty: qty })
        }).then(r => r.json()).then(renderCart).catch(() => {});
      };

      window.cartRemove = function(index) {
        fetch('/cart/remove/' + index, {
          method: 'DELETE',
          headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        }).then(r => r.json()).then(renderCart).catch(() => {});
      };

      // Checkout: buka modal
      document.getElementById('btn-checkout-cart')?.addEventListener('click', function() {
        var modal = new bootstrap.Modal(document.getElementById('cartCheckoutModal'));
        modal.show();
      });

      // Load cart on page load
      fetchCart();
    })();
  </script>

  @stack('scripts')
</body>
</html>
