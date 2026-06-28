<x-layouts.store :title="\App\Models\Setting::get('site_name', config('app.name'))">

  @push('styles')
  <style>
    .price-filter-card { border: 1px solid #eef0f3; border-radius: 20px; box-shadow: 0 12px 30px rgba(15,23,42,.06); padding: 18px; background: #fff; }
    .price-filter-label { color: #111827; font-size: .78rem; font-weight: 700; letter-spacing: .02em; }
    .price-filter-field .input-group-text, .price-filter-field .form-control, .price-filter-field .form-select { border-color: #e5e7eb; min-height: 46px; }
    .price-filter-field .input-group-text { border-radius: 14px 0 0 14px; background: #f8fafc; color: #6b7280; font-weight: 700; }
    .price-filter-field .form-control { border-radius: 0 14px 14px 0; }
    .price-filter-field .form-select { border-radius: 14px; }
    .price-filter-field .form-control:focus, .price-filter-field .form-select:focus { border-color: #111827; box-shadow: 0 0 0 .18rem rgba(17,24,39,.08); }
    .price-filter-actions .btn { border-radius: 14px; min-height: 46px; font-weight: 700; }
    @media (max-width: 576px) {
      .product-posts .product-category-scroll {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 6px !important;
        padding: 0 12px !important;
        overflow: visible !important;
      }
      .product-posts .product-category-scroll .btn-product-post-category {
        width: 100% !important;
        margin: 0 !important;
        padding: 6px 4px !important;
        font-size: 10px !important;
        line-height: 1.15 !important;
        white-space: normal !important;
        min-height: 34px;
        border-radius: 999px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
      }
    }
  </style>
  @endpush

  <!-- Hero Section -->
  @if ($banners->isNotEmpty())
  <section id="hero" class="hero section">
    <div class="container-fluid p-0" data-aos="fade">
      <div class="hero-slider swiper init-swiper">
        <script type="application/json" class="swiper-config">
          {"loop":true,"speed":1000,"effect":"fade","autoplay":{"delay":5000},"slidesPerView":1,"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"}}
        </script>
        <div class="swiper-wrapper">
          @foreach ($banners as $banner)
          <div class="swiper-slide">
            <div class="hero-item">
              <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" class="img-fluid">
            </div>
          </div>
          @endforeach
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
  </section>
  @endif

  <!-- Category Posts Section -->
  @if ($categories->isNotEmpty())
  <section id="category-posts" class="category-posts section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="category-posts-slider swiper init-swiper">
        <script type="application/json" class="swiper-config">
          {"loop":true,"speed":900,"autoplay":{"delay":3500},"slidesPerView":1,"spaceBetween":40,"centeredSlides":false,"breakpoints":{"768":{"slidesPerView":1.5,"spaceBetween":30},"1200":{"slidesPerView":3,"spaceBetween":40}},"pagination":{"el":".swiper-pagination","clickable":true}}
        </script>
        <div class="swiper-wrapper">
          @foreach ($categories as $cat)
          <div class="swiper-slide">
            <article class="category-card position-relative">
              <div class="category-image">
                @if ($cat->image_path)
                  <img src="{{ asset('storage/' . $cat->image_path) }}" alt="{{ $cat->name }}" loading="lazy">
                @else
                  <img src="{{ asset('assets/img/category/' . $cat->slug . '.svg') }}" alt="{{ $cat->name }}" loading="lazy">
                @endif
                <div class="category-badge">{{ $cat->name }}</div>
              </div>
              <div class="category-content">
                <a href="{{ route('categories.show', $cat) }}" class="btn btn-dark stretched-link">
                  <span>Lihat Lebih Banyak</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </article>
          </div>
          @endforeach
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>
  @endif

  <!-- Product Posts Section -->
  <section id="product-posts" class="product-posts section">
    <!-- Filter Tabs -->
    <div class="container section-title product-category-scroll" data-aos="fade-up">
      @foreach ($tags as $tag)
        <a href="{{ route('home', ['tag' => $tag->slug]) }}"
           class="btn btn-product-post-category me-2 rounded-0 js-product-filter {{ $activeTag?->id === $tag->id ? 'active' : '' }}">
          {{ $tag->name }}
        </a>
      @endforeach
    </div>

    <div class="container px-lg-5 mb-4" data-aos="fade-up">
      <form action="{{ route('home') }}" method="GET" class="price-filter-card row g-3 align-items-end js-price-filter-form">
        @if ($activeTag)
          <input type="hidden" name="tag" value="{{ $activeTag->slug }}">
        @endif
        @if ($activeCategory)
          <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
        @endif
        <div class="col-12 col-md-6 col-lg-3 price-filter-field">
          <label class="form-label price-filter-label mb-2"><i class="bi bi-arrow-down-circle me-1"></i>Harga Min</label>
          <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" min="0" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="0">
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3 price-filter-field">
          <label class="form-label price-filter-label mb-2"><i class="bi bi-arrow-up-circle me-1"></i>Harga Max</label>
          <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" min="0" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="0">
          </div>
        </div>
        <div class="col-12 col-lg-3 price-filter-field">
          <label class="form-label price-filter-label mb-2"><i class="bi bi-sort-down me-1"></i>Sort Harga</label>
          <select name="sort" class="form-select">
            <option value="">Terbaru</option>
            <option value="price_asc" @selected(request('sort') === 'price_asc')>Termurah</option>
            <option value="price_desc" @selected(request('sort') === 'price_desc')>Termahal</option>
          </select>
        </div>
        <div class="col-12 col-lg-3 d-flex gap-2 price-filter-actions">
          <button class="btn btn-dark flex-fill" type="submit">Filter</button>
          <a href="{{ route('home') }}" class="btn btn-outline-secondary flex-fill js-product-filter">Reset</a>
        </div>
      </form>
    </div>

    <div id="product-list" class="container px-lg-5" data-aos="fade-up" data-aos-delay="100">
      <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 gy-4 align-items-stretch">
        @forelse ($products as $product)
        <div class="col" data-aos="fade-up" data-aos-delay="200">
          <article class="card-post h-100 d-flex flex-column position-relative">
            <div class="post-img position-relative overflow-hidden">
              @if ($product->primaryImage)
                <img src="{{ $product->primaryImage->url }}" class="img-fluid w-100" alt="{{ $product->name }}" loading="lazy">
              @else
                <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px"><span class="text-muted">Tanpa Gambar</span></div>
              @endif
            </div>
            <div class="content d-flex flex-column flex-grow-1">
              <div class="meta d-flex align-items-center flex-wrap gap-2">
                @foreach ($product->tags as $tag)
                  <span class="cat-badge">{{ $tag->name }}</span>
                @endforeach
              </div>
              <h3 class="title" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.7em;">{{ $product->name }}</h3>
              <h5 class="mb-0">{{ $product->priceRangeLabel() }}</h5>
              <div class="mt-auto pt-3 text-center">
                <a href="{{ route('products.show', $product) }}" class="btn btn-dark ps-5 pe-5 stretched-link">Detail</a>
              </div>
            </div>
          </article>
        </div>
        @empty
          <div class="col-12 text-center text-muted py-5">Belum ada produk.</div>
        @endforelse
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.js-product-filter').forEach((link) => {
        link.addEventListener('click', async (event) => {
          event.preventDefault();
          loadProductList(link.href, link);
        });
      });

      document.querySelector('.js-price-filter-form')?.addEventListener('submit', (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        const params = new URLSearchParams(new FormData(form));
        [...params.entries()].forEach(([key, value]) => {
          if (!value) params.delete(key);
        });
        const query = params.toString();
        loadProductList(`${form.action}${query ? `?${query}` : ''}`);
      });

      async function loadProductList(url, activeLink = null) {
        const productList = document.querySelector('#product-list');

        if (!productList) {
          window.location.href = url;
          return;
        }

        productList.style.opacity = '0.5';

        try {
          const response = await fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
            },
          });

          if (!response.ok) {
            throw new Error('Gagal memuat produk.');
          }

          const html = await response.text();
          const doc = new DOMParser().parseFromString(html, 'text/html');
          const nextProductList = doc.querySelector('#product-list');

          if (!nextProductList) {
            throw new Error('Daftar produk tidak ditemukan.');
          }

          productList.innerHTML = nextProductList.innerHTML;
          window.history.pushState({}, '', url);

          document.querySelectorAll('.js-product-filter').forEach((item) => item.classList.remove('active'));
          activeLink?.classList.add('active');

          document.querySelector('#product-posts')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
          console.error(error);
          window.location.href = url;
        } finally {
          productList.style.opacity = '';
        }
      }
    });
  </script>
</x-layouts.store>
