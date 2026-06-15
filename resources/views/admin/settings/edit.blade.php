@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Pengaturan</h4>
    <p class="text-muted small mb-0">Konfigurasi WhatsApp & informasi kontak</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" x-data="settingsForm()">
    @csrf
    @method('PUT')

    {{-- WhatsApp --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-whatsapp me-2 text-success"></i>Pengaturan WhatsApp</h6></div>
        <div class="card-body vstack gap-4">
            <div>
                <label for="whatsapp_number" class="form-label fw-medium">Nomor WhatsApp Admin</label>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" class="form-control" placeholder="6281234567890">
                <div class="form-text">Format internasional tanpa <code>+</code> atau spasi.</div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <label for="checkout_template" class="form-label fw-medium">Template Checkout Langsung</label>
                    <textarea name="checkout_template" id="checkout_template" rows="14" x-model="template" @input.debounce.400ms="refreshPreview()" class="form-control font-monospace small">{{ old('checkout_template', $settings['checkout_template']) }}</textarea>
                    <div class="form-text mt-2">
                        Styling WA: <code>*tebal*</code> <code>_miring_</code> <code>~coret~</code> <code>```mono```</code>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label fw-medium">Pratinjau (data contoh)</label>
                    <div class="rounded-3 p-3" style="background:#e5ddd5;min-height:16rem">
                        <div class="bg-white rounded-3 shadow-sm p-3 small text-dark" style="max-width:22rem" x-html="preview"></div>
                    </div>
                </div>
            </div>

            {{-- Placeholders --}}
            <div class="border-top pt-3">
                <p class="fw-semibold small text-dark mb-2">Placeholder checkout langsung</p>
                <div class="row g-2">
                    @foreach ($placeholders as $code => $desc)
                        <div class="col-auto">
                            <button type="button" @click="insertPlaceholder('{{ $code }}')" class="btn btn-sm btn-outline-primary font-monospace">{{ $code }}</button>
                            <small class="text-muted ms-1">{{ $desc }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Cart template --}}
            <div class="border-top pt-3">
                <label for="cart_template" class="form-label fw-medium">Template Keranjang (Multi-Item)</label>
                <textarea name="cart_template" id="cart_template" rows="8" class="form-control font-monospace small">{{ old('cart_template', $settings['cart_template']) }}</textarea>
                <div class="form-text">Placeholder: <code>{customer_name}</code> <code>{items}</code> <code>{grand_total}</code></div>
            </div>
        </div>
    </div>

    {{-- Kontak --}}
    <div class="card border-0 shadow-sm mb-4">
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
                    <label class="form-label fw-medium">No. WhatsApp</label>
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

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan Pengaturan</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
    </div>
</form>

@push('scripts')
<script>
    function settingsForm() {
        return {
            template: @js(old('checkout_template', $settings['checkout_template'])),
            preview: @js($previewHtml),
            async refreshPreview() {
                try {
                    const res = await fetch('{{ route("admin.settings.preview") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ checkout_template: this.template }),
                    });
                    if (res.ok) { const data = await res.json(); this.preview = data.html; }
                } catch (e) {}
            },
            insertPlaceholder(code) {
                const el = document.getElementById('checkout_template');
                const start = el.selectionStart ?? this.template.length;
                const end = el.selectionEnd ?? this.template.length;
                this.template = this.template.slice(0, start) + code + this.template.slice(end);
                this.$nextTick(() => { el.focus(); el.selectionStart = el.selectionEnd = start + code.length; this.refreshPreview(); });
            },
        };
    }
</script>
@endpush
@endsection
