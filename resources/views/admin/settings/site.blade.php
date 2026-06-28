@extends('layouts.admin')
@section('title', 'Site Setting')

@section('content')
<div class="mb-4">
 <h4 class="fw-bold mb-1">Site Setting</h4>
 <p class="text-muted small mb-0">Pengaturan nama website, kontak, dan link sosial media</p>
</div>

@if ($errors->any())
 <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<form method="POST" action="{{ route('admin.site-settings.update') }}">
 @csrf
 @method('PUT')

 <div class="card mb-4">
  <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-globe me-2 text-primary"></i>Informasi Website</h6></div>
  <div class="card-body">
   <div class="row g-3">
    <div class="col-lg-6">
      <label for="site_name" class="form-label fw-medium">Nama Website</label>
      <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="form-control" placeholder="Nama toko / website">
      <div class="form-text">Dipakai di title halaman, brand header, dan footer.</div>
    </div>
   </div>
  </div>
 </div>

 <div class="card mb-4">
  <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-geo-alt me-2 text-primary"></i>Informasi Kontak</h6></div>
  <div class="card-body">
   <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label fw-medium">Alamat</label>
      <input type="text" name="contact_address" value="{{ old('contact_address', $settings['contact_address']) }}" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label fw-medium">Email</label>
      <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label fw-medium">No. WhatsApp / Telepon</label>
      <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label fw-medium">Jam Kerja</label>
      <input type="text" name="contact_hours" value="{{ old('contact_hours', $settings['contact_hours']) }}" class="form-control" placeholder="Senin-Minggu : 09.00 - 17.00">
    </div>
    <div class="col-12">
      <label class="form-label fw-medium">Google Maps Embed URL</label>
      <textarea name="contact_maps_embed" rows="3" class="form-control font-monospace small">{{ old('contact_maps_embed', $settings['contact_maps_embed']) }}</textarea>
      <div class="form-text">Tempel kode iframe dari Google Maps.</div>
    </div>
   </div>
  </div>
 </div>

 <div class="card mb-4">
  <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-share me-2 text-primary"></i>Link Sosial Media</h6></div>
  <div class="card-body">
   <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label fw-medium">Facebook URL</label>
      <input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}" class="form-control" placeholder="https://facebook.com/... atau #">
    </div>
    <div class="col-md-4">
      <label class="form-label fw-medium">Instagram URL</label>
      <input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}" class="form-control" placeholder="https://instagram.com/... atau #">
    </div>
    <div class="col-md-4">
      <label class="form-label fw-medium">Threads URL</label>
      <input type="text" name="social_threads" value="{{ old('social_threads', $settings['social_threads']) }}" class="form-control" placeholder="https://threads.net/... atau #">
    </div>
   </div>
  </div>
 </div>

 <div class="card mb-4">
  <div class="card-header bg-white d-flex align-items-center justify-content-between">
   <h6 class="mb-0 fw-semibold"><i class="bi bi-link-45deg me-2 text-primary"></i>Tautan Footer Toko</h6>
   <span class="badge bg-light text-muted border">{{ count($settings['footer_links'] ?? []) }} link</span>
  </div>
  <div class="card-body">
   <p class="text-muted small mb-3"><i class="bi bi-info-circle me-1"></i>Atur teks, alamat tujuan, dan status tampil tiap link tambahan di footer toko. Nonaktifkan toggle <strong>Tampilkan</strong> untuk menyembunyikan link tanpa menghapusnya.</p>
   <div class="vstack gap-3">
    @foreach (($settings['footer_links'] ?? []) as $idx => $link)
     <div class="border rounded-3 p-3 bg-light-subtle">
      <div class="d-flex align-items-center gap-2 mb-3">
       <span class="badge rounded-pill bg-primary">Link {{ $idx + 1 }}</span>
       <span class="text-muted small">Tautan di bagian bawah toko</span>
      </div>
      <div class="row g-3 align-items-end">
       <div class="col-12 col-md-4">
        <label class="form-label small fw-semibold mb-1">Label <span class="text-muted fw-normal">(teks tampil)</span></label>
        <input type="text" name="footer_links[{{ $idx }}][label]" value="{{ old('footer_links.' . $idx . '.label', $link['label'] ?? '') }}" class="form-control" placeholder="Shopee">
       </div>
       <div class="col-12 col-md-5">
        <label class="form-label small fw-semibold mb-1">Href / URL <span class="text-muted fw-normal">(tujuan)</span></label>
        <div class="input-group">
         <span class="input-group-text bg-white text-muted"><i class="bi bi-link-45deg"></i></span>
         <input type="text" name="footer_links[{{ $idx }}][href]" value="{{ old('footer_links.' . $idx . '.href', $link['href'] ?? '#') }}" class="form-control" placeholder="https://... atau #">
        </div>
       </div>
       <div class="col-12 col-md-3">
        <label class="form-label small fw-semibold mb-1 d-block">Status</label>
        <div class="form-check form-switch ps-0 d-flex align-items-center gap-2">
         <input type="hidden" name="footer_links[{{ $idx }}][is_visible]" value="0">
         <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" name="footer_links[{{ $idx }}][is_visible]" value="1" id="footer-visible-{{ $idx }}" @checked(old('footer_links.' . $idx . '.is_visible', $link['is_visible'] ?? false))>
         <label class="form-check-label small" for="footer-visible-{{ $idx }}">Tampilkan</label>
        </div>
       </div>
      </div>
     </div>
    @endforeach
    @if (empty($settings['footer_links'] ?? []))
     <div class="text-center text-muted small py-3 border rounded-3 bg-light-subtle">
      <i class="bi bi-link-45deg d-block fs-4 mb-1"></i>Belum ada link footer.
     </div>
    @endif
   </div>
  </div>
 </div>

 <div class="d-flex gap-2">
  <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan Site Setting</button>
  <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
 </div>
</form>
@endsection
