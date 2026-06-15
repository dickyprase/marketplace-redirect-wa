<x-layouts.store :title="'Kategori: ' . $category->name">

  <div class="page-title">
    <div class="heading">
      <div class="container">
        <div class="row d-flex justify-content-center text-center">
          <div class="col-lg-8">
            <h1 class="heading-title">{{ $category->name }}</h1>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="{{ route('home') }}">Beranda</a></li>
          <li class="current">Kategori</li>
          <li class="current">{{ $category->name }}</li>
        </ol>
      </div>
    </nav>
  </div>

  <section id="product-posts" class="product-posts section">
    <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4 align-items-stretch">
        @forelse ($products as $product)
        <div class="col-6 col-lg-2" data-aos="fade-up" data-aos-delay="200">
          <article class="card-post h-100">
            <div class="post-img position-relative overflow-hidden">
              @if ($product->primaryImage)
                <img src="{{ $product->primaryImage->url }}" class="img-fluid w-100" alt="{{ $product->name }}" loading="lazy">
              @else
                <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px"><span class="text-muted">Tanpa Gambar</span></div>
              @endif
            </div>
            <div class="content">
              <div class="meta d-flex align-items-center flex-wrap gap-2">
                @foreach ($product->tags as $tag)
                  <span class="cat-badge">{{ $tag->name }}</span>
                @endforeach
              </div>
              <h3 class="title">{{ $product->name }}</h3>
              <h5>{{ $product->priceRangeLabel() }}</h5>
              <div class="mt-3 text-center">
                <a href="{{ route('products.show', $product) }}" class="btn btn-dark ps-5 pe-5">Detail</a>
              </div>
            </div>
          </article>
        </div>
        @empty
          <div class="col-12 text-center text-muted py-5">Belum ada produk dalam kategori ini.</div>
        @endforelse
      </div>
      <div class="mt-4 d-flex justify-content-center">{{ $products->links() }}</div>
    </div>
  </section>

</x-layouts.store>
