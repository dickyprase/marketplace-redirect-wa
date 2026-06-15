<x-layouts.store title="Kontak Kami">

  <div class="page-title">
    <div class="heading">
      <div class="container">
        <div class="row d-flex justify-content-center text-center">
          <div class="col-lg-8">
            <h1 class="heading-title">Kontak Kami</h1>
          </div>
        </div>
      </div>
    </div>
    <nav class="breadcrumbs">
      <div class="container">
        <ol>
          <li><a href="{{ route('home') }}">Beranda</a></li>
          <li class="current">Kontak Kami</li>
        </ol>
      </div>
    </nav>
  </div>

  <section id="contact" class="contact section">
    <div class="container">
      <div class="contact-wrapper">
        <div class="contact-info-panel">
          <div class="contact-info-header">
            <h3>Informasi Kontak Kami</h3>
            <p>Jangan ragu untuk menghubungi kami.</p>
          </div>

          <div class="contact-info-cards">
            @if (!empty($contact['address']))
            <div class="info-card">
              <div class="icon-container"><i class="bi bi-pin-map-fill"></i></div>
              <div class="card-content">
                <h4>Lokasi Kami</h4>
                <p>{{ $contact['address'] }}</p>
              </div>
            </div>
            @endif

            @if (!empty($contact['email']))
            <div class="info-card">
              <div class="icon-container"><i class="bi bi-envelope-open"></i></div>
              <div class="card-content">
                <h4>Email Kami</h4>
                <p>{{ $contact['email'] }}</p>
              </div>
            </div>
            @endif

            @if (!empty($contact['phone']))
            <div class="info-card">
              <div class="icon-container"><i class="bi bi-telephone-fill"></i></div>
              <div class="card-content">
                <h4>WhatsApp Kami</h4>
                <p>{{ $contact['phone'] }}</p>
              </div>
            </div>
            @endif

            @if (!empty($contact['hours']))
            <div class="info-card">
              <div class="icon-container"><i class="bi bi-clock-history"></i></div>
              <div class="card-content">
                <h4>Jam Kerja</h4>
                <p>{{ $contact['hours'] }}</p>
              </div>
            </div>
            @endif
          </div>

          <div class="social-links-panel">
            <h5>Follow Us</h5>
            <div class="social-icons">
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-instagram"></i></a>
              <a href="#"><i class="bi bi-threads"></i></a>
            </div>
          </div>
        </div>

        <div class="contact-form-panel">
          <div class="map-container">
            @if (!empty($contact['maps_embed']))
              {!! $contact['maps_embed'] !!}
            @else
              <div class="d-flex align-items-center justify-content-center bg-light" style="height:100%;min-height:400px"><span class="text-muted">Peta belum dikonfigurasi</span></div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

</x-layouts.store>
