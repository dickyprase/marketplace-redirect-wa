<x-layouts.store :title="config('app.name')">

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
          {"loop":true,"speed":600,"autoplay":{"delay":1500},"slidesPerView":1,"spaceBetween":40,"centeredSlides":false,"breakpoints":{"768":{"slidesPerView":1.5,"spaceBetween":30},"1200":{"slidesPerView":3,"spaceBetween":40}},"pagination":{"el":".swiper-pagination","clickable":true}}
        </script>
        <div class="swiper-wrapper">
          @foreach ($categories as $cat)
          <div class="swiper-slide">
            <article class="category-card">
              <div class="category-image">
                @if ($cat->image_path)
                  <img src="{{ asset('storage/' . $cat->image_path) }}" alt="{{ $cat->name }}" loading="lazy">
                @else
                  <div style="height:400px;display:flex;align-items:center;justify-content:center;background:#f0f1f2;"><span class="text-muted">{{ $cat->name }}</span></div>
                @endif
                <div class="category-badge">{{ $cat->name }}</div>
              </div>
              <div class="category-content">
                <a href="{{ route('categories.show', $cat) }}" class="btn btn-dark">
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
      <a href="{{ route('home') }}" class="btn btn-product-post-category me-2 rounded-0 {{ !$activeTag ? 'active' : '' }}">Semua</a>
      @foreach ($tags as $tag)
        <a href="{{ route('home', ['tag' => $tag->slug]) }}"
           class="btn btn-product-post-category me-2 rounded-0 {{ $activeTag?->id === $tag->id ? 'active' : '' }}">
          {{ $tag->name }}
        </a>
      @endforeach
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4 align-items-stretch">
        @forelse ($products as $product)
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
          <article class="card-post h-100 d-flex flex-column">
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
                <a href="{{ route('products.show', $product) }}" class="btn btn-dark ps-5 pe-5">Detail</a>
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

</x-layouts.store>
