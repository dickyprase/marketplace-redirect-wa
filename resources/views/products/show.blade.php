<x-layouts.public :title="$product->name">
    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali ke katalog</a>

    @php
        $sizesData = $product->sizes->map(fn ($s) => [
            'id' => $s->id,
            'label' => $s->label,
            'price' => (float) $s->price,
            'price_label' => $s->formatted_price,
            'status' => $s->stock_status,
            'orderable' => $s->isOrderable(),
        ])->values();
        $hasSizes = $product->hasSizes();
        $images = $product->images;
    @endphp

    <div x-data="productPage({
            hasSizes: {{ $hasSizes ? 'true' : 'false' }},
            sizes: {{ $sizesData->toJson() }},
            basePriceLabel: @js($product->priceRangeLabel()),
            images: {{ $images->map(fn ($i) => $i->url)->toJson() }},
         })"
         class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-4">

        {{-- Galeri --}}
        <div>
            <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
                @if ($images->isNotEmpty())
                    <img :src="activeImage" alt="{{ $product->name }}" class="object-cover w-full h-full">
                @else
                    <span class="text-gray-300">Tanpa Gambar</span>
                @endif
            </div>

            @if ($images->count() > 1)
                <div class="grid grid-cols-5 gap-2 mt-3">
                    @foreach ($images as $i => $image)
                        <button type="button" @click="activeImage = images[{{ $i }}]"
                                class="aspect-square rounded-lg overflow-hidden border-2"
                                :class="activeImage === images[{{ $i }}] ? 'border-indigo-500' : 'border-transparent'">
                            <img src="{{ $image->url }}" class="object-cover w-full h-full" alt="">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Detail + form --}}
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                <x-stock-badge :status="$product->effectiveStatus()" />
            </div>

            <p class="text-indigo-600 font-bold text-2xl mt-2" x-text="priceLabel"></p>

            @if ($product->size_chart)
                <button type="button" @click="showChart = true"
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 underline">
                    Size Chart
                </button>
            @endif

            @if ($product->description)
                <div class="prose prose-sm max-w-none text-gray-600 mt-4">
                    {!! $product->description !!}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Form Pemesanan</h2>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (! $product->isOrderableNow())
                    <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                        Produk ini sedang tidak tersedia.
                    </div>
                @else
                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size_id" :value="selectedSizeId">

                        {{-- Pilihan ukuran --}}
                        @if ($hasSizes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Ukuran</label>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="size in sizes" :key="size.id">
                                        <button type="button"
                                                @click="size.orderable && selectSize(size)"
                                                :disabled="!size.orderable"
                                                class="px-3 py-2 rounded-lg border text-sm font-medium transition"
                                                :class="{
                                                    'border-indigo-500 bg-indigo-50 text-indigo-700': selectedSizeId === size.id,
                                                    'border-gray-300 text-gray-700 hover:border-gray-400': selectedSizeId !== size.id && size.orderable,
                                                    'border-gray-200 text-gray-300 line-through cursor-not-allowed': !size.orderable,
                                                }"
                                                x-text="size.label"></button>
                                    </template>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pembeli</label>
                            <input type="text" name="customer_name" id="customer_name" required
                                   value="{{ old('customer_name') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Nama lengkap Anda">
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah (QTY)</label>
                            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Permintaan khusus">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-4 py-3 transition">
                            <span x-text="buttonLabel"></span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Modal Size Chart --}}
        @if ($product->size_chart)
            <div x-show="showChart" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                 @click.self="showChart = false" @keydown.escape.window="showChart = false">
                <div class="bg-white rounded-xl max-w-2xl w-full max-h-[80vh] overflow-auto p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Size Chart</h3>
                        <button type="button" @click="showChart = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <div class="prose prose-sm max-w-none sizechart-content">
                        {!! $product->size_chart !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function productPage(config) {
                const preOrder = @js(\App\Models\Product::STATUS_PREORDER);
                return {
                    hasSizes: config.hasSizes,
                    sizes: config.sizes,
                    images: config.images,
                    activeImage: config.images.length ? config.images[0] : null,
                    selectedSizeId: null,
                    selectedSize: null,
                    showChart: false,
                    priceLabel: config.basePriceLabel,
                    buttonLabel: @js($product->isPreOrder() ? 'Pre-Order via WhatsApp' : 'Pesan via WhatsApp'),
                    selectSize(size) {
                        this.selectedSizeId = size.id;
                        this.selectedSize = size;
                        this.priceLabel = size.price_label;
                        this.buttonLabel = size.status === preOrder ? 'Pre-Order via WhatsApp' : 'Pesan via WhatsApp';
                    },
                };
            }
        </script>
    @endpush
</x-layouts.public>
