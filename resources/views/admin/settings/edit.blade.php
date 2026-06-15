<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan</h2>
    </x-slot>

    <div class="py-8" x-data="settingsForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white shadow-sm rounded-lg p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Nomor WhatsApp --}}
                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Admin</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number"
                           value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="6281234567890">
                    <p class="text-xs text-gray-400 mt-1">Format internasional tanpa <code>+</code> atau spasi.</p>
                </div>

                {{-- Template checkout langsung --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="checkout_template" class="block text-sm font-medium text-gray-700 mb-1">
                            Template Pesan Checkout Langsung
                        </label>
                        <textarea name="checkout_template" id="checkout_template" rows="16"
                                  x-model="template" @input.debounce.400ms="refreshPreview()"
                                  class="w-full font-mono text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('checkout_template', $settings['checkout_template']) }}</textarea>
                        <div class="mt-3 text-xs text-gray-500">
                            <p class="font-semibold text-gray-600 mb-1">Styling WhatsApp:</p>
                            <p><code>*tebal*</code> &rarr; <strong>tebal</strong> &middot;
                               <code>_miring_</code> &rarr; <em>miring</em> &middot;
                               <code>~coret~</code> &rarr; <del>coret</del> &middot;
                               <code>```mono```</code> &rarr; <code class="bg-gray-200 px-1 rounded">mono</code></p>
                        </div>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-gray-700 mb-1">Pratinjau (data contoh)</span>
                        <div class="rounded-lg border border-gray-200 bg-[#e5ddd5] p-4 min-h-[20rem]">
                            <div class="bg-white rounded-lg shadow-sm p-3 text-sm text-gray-800 leading-relaxed max-w-sm"
                                 x-html="preview"></div>
                        </div>
                    </div>
                </div>

                {{-- Daftar placeholder checkout --}}
                <div class="border-t border-gray-100 pt-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Placeholder checkout langsung</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 text-sm">
                        @foreach ($placeholders as $code => $desc)
                            <div class="flex items-start gap-2">
                                <button type="button" @click="insertPlaceholder('{{ $code }}')"
                                        class="font-mono text-indigo-600 hover:text-indigo-800 hover:underline shrink-0">{{ $code }}</button>
                                <span class="text-gray-500">— {{ $desc }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Template keranjang (multi-item) --}}
                <div class="border-t border-gray-100 pt-4">
                    <label for="cart_template" class="block text-sm font-medium text-gray-700 mb-1">
                        Template Pesan Keranjang (Multi-Item)
                    </label>
                    <textarea name="cart_template" id="cart_template" rows="10"
                              class="w-full font-mono text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('cart_template', $settings['cart_template']) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">
                        Placeholder: <code>{customer_name}</code>, <code>{notes_line}</code>, <code>{items}</code>, <code>{grand_total}</code>
                    </p>
                </div>

                {{-- Informasi Kontak --}}
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Informasi Kontak</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <input type="text" name="contact_address" value="{{ old('contact_address', $settings['contact_address']) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Kerja</label>
                            <input type="text" name="contact_hours" value="{{ old('contact_hours', $settings['contact_hours']) }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Senin-Minggu : 09.00 - 17.00">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Google Maps Embed URL</label>
                        <textarea name="contact_maps_embed" rows="3"
                                  class="w-full font-mono text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('contact_maps_embed', $settings['contact_maps_embed']) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Tempel kode iframe <code>&lt;iframe ...&gt;&lt;/iframe&gt;</code> dari Google Maps.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">
                        Simpan Pengaturan
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function settingsForm() {
            return {
                template: @js(old('checkout_template', $settings['checkout_template'])),
                preview: @js($previewHtml),
                async refreshPreview() {
                    try {
                        const res = await fetch('{{ route('admin.settings.preview') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ checkout_template: this.template }),
                        });
                        if (res.ok) {
                            const data = await res.json();
                            this.preview = data.html;
                        }
                    } catch (e) {}
                },
                insertPlaceholder(code) {
                    const el = document.getElementById('checkout_template');
                    const start = el.selectionStart ?? this.template.length;
                    const end = el.selectionEnd ?? this.template.length;
                    this.template = this.template.slice(0, start) + code + this.template.slice(end);
                    this.$nextTick(() => {
                        el.focus();
                        el.selectionStart = el.selectionEnd = start + code.length;
                        this.refreshPreview();
                    });
                },
            };
        }
    </script>
</x-app-layout>
