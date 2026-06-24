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

 <div class="d-flex gap-2">
  <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan Site Setting</button>
  <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
 </div>
</form>
@endsection
