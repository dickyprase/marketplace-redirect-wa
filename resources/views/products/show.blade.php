<x-layouts.store :title="$product->name">
@php
    $hasSizes = $product->hasSizes();
    $images = $product->images;
@endphp

<main class="main">
  <section id="detail-product" class="detail-product section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="detail-product-1">
        <div class="row">
          <!-- Product Image -->
          <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="product-card" data-aos="fade-up">
              <div class="product-image">
                @if ($images->isNotEmpty())
                  <img id="main-product-image" src="{{ $images->first()->url }}" alt="{{ $product->name }}" class="img-fluid rounded">
                @else
                  <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height:400px"><span class="text-muted">Tanpa Gambar</span></div>
                @endif
              </div>
              @if ($images->count() > 1)
              <div class="product-info">
                <div class="product-image-list">
                  @foreach ($images as $img)
                    <img src="{{ $img->url }}" alt="{{ $product->name }}" class="img-fluid rounded gallery-thumb" onclick="document.getElementById('main-product-image').src=this.src">
                  @endforeach
                </div>
              </div>
              @endif
            </div>
          </div>

          <!-- Product Content -->
          <div class="col-lg-6">
            <div class="product-content" data-aos="fade-up" data-aos-delay="200">
              <div class="content-header">
                <div class="d-flex align-items-center gap-3">
                  <h3 class="mb-0">{{ $product->name }}</h3>
                  @if ($product->category)
                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                  @endif
                </div>
                @if ($product->tags->isNotEmpty())
                <div class="mt-2">
                  @foreach ($product->tags as $tag)
                    <span class="cat-badge">{{ $tag->name }}</span>
                  @endforeach
                </div>
                @endif
              </div>

              <div class="content-body">
                @if ($product->description)
                  <div class="mb-3">{!! $product->description !!}</div>
                @endif

                @if ($product->size_chart)
                  <p class="mb-3"><a href="#" data-bs-toggle="modal" data-bs-target="#sizeChartModal" class="text-decoration-underline">Lihat Size Chart</a></p>
                @endif

                @if ($hasSizes)
                <div class="size-areas">
                  <h4>Size :</h4>
                  <div class="tags">
                    @foreach ($product->sizes as $size)
                      <span class="size-tag" data-size-id="{{ $size->id }}"
                            data-price="{{ $size->formatted_price }}"
                            data-status="{{ $size->stock_status }}"
                            data-orderable="{{ $size->isOrderable() ? '1' : '0' }}"
                            @if (!$size->isOrderable()) style="opacity:0.4;text-decoration:line-through;pointer-events:none;" @endif
                            onclick="selectSize(this)">{{ $size->label }}</span>
                    @endforeach
                  </div>
                  <input type="hidden" name="selected_size_id" id="selected-size-id" value="">
                </div>
                @endif

                <div class="price mt-4">
                  <h4>Harga :</h4>
                  <h2 id="display-price">{{ $product->priceRangeLabel() }}</h2>
                </div>

                @if (! $product->isOrderableNow())
                  <div class="alert alert-danger mt-3">Produk ini sedang tidak tersedia.</div>
                @else
                <div class="d-grid gap-2 mt-4">
                  <button class="btn btn-light btn-outline-dark" onclick="handleAddToCart()">
                    <i class="bi bi-cart me-2"></i>Tambah Ke Keranjang
                  </button>
                  <button class="btn btn-dark" onclick="openCheckoutModal()">
                    Checkout Sekarang
                  </button>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Checkout Langsung -->
  <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="checkoutModalLabel">Checkout — {{ $product->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="checkoutDirectForm" method="POST" action="{{ route('checkout.process') }}">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <input type="hidden" name="size_id" id="checkout-size-id" value="">
          <input type="hidden" name="quantity" value="1">
          <div class="modal-body">
            <div class="mb-3">
              <label for="checkout-name" class="form-label">Nama Pembeli <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="checkout-name" name="customer_name" required placeholder="Nama lengkap Anda">
            </div>
            <div class="mb-3">
              <label for="checkout-address" class="form-label">Alamat <span class="text-danger">*</span></label>
              <textarea class="form-control" id="checkout-address" name="address" rows="2" required placeholder="Alamat lengkap pengiriman"></textarea>
            </div>
            <div class="mb-3">
              <label for="checkout-notes" class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
              <textarea class="form-control" id="checkout-notes" name="notes" rows="2" placeholder="Warna, ukuran, atau permintaan khusus"></textarea>
            </div>
            <div id="checkout-size-warning" class="alert alert-warning py-2 mb-0" style="display:none;">
              Silakan pilih ukuran terlebih dahulu.
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

  <!-- Modal Size Chart -->
  @if ($product->size_chart)
  <div class="modal fade" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sizeChartModalLabel">Size Chart — {{ $product->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {!! $product->size_chart !!}
        </div>
      </div>
    </div>
  </div>
  @endif
</main>

@push('scripts')
<script>
  var PRODUCT_ID = {{ $product->id }};
  var HAS_SIZES  = {{ $hasSizes ? 'true' : 'false' }};
  var selectedSizeId = null;

  function selectSize(el) {
    document.querySelectorAll('.size-tag').forEach(function(s) { s.classList.remove('active'); });
    el.classList.add('active');
    selectedSizeId = parseInt(el.dataset.sizeId);
    document.getElementById('selected-size-id').value = selectedSizeId;
    document.getElementById('display-price').textContent = el.dataset.price;
    document.getElementById('checkout-size-warning').style.display = 'none';
  }

  function validateSize() {
    if (HAS_SIZES && !selectedSizeId) {
      return false;
    }
    return true;
  }

  function handleAddToCart() {
    if (!validateSize()) {
      alert('Silakan pilih ukuran terlebih dahulu.');
      return;
    }
    addToCart(PRODUCT_ID, selectedSizeId, 1);
    var oc = new bootstrap.Offcanvas(document.getElementById('offcanvasCart'));
    oc.show();
  }

  function openCheckoutModal() {
    if (!validateSize()) {
      document.getElementById('checkout-size-warning').style.display = 'block';
      return;
    }
    document.getElementById('checkout-size-id').value = selectedSizeId || '';
    document.getElementById('checkout-size-warning').style.display = 'none';
    var modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    modal.show();
  }
</script>
@endpush
</x-layouts.store>
