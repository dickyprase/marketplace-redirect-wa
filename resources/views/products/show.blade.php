<x-layouts.public :title="$product->name">
    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali ke katalog</a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-4">
        {{-- Product detail --}}
        <div>
            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
                @if ($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}"
                         alt="{{ $product->name }}" class="object-cover w-full h-full">
                @else
                    <span class="text-gray-300">Tanpa Gambar</span>
                @endif
            </div>

            <div class="mt-5">
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
                    <x-stock-badge :status="$product->stock_status" />
                </div>
                <p class="text-indigo-600 font-bold text-2xl mt-2">{{ $product->formatted_price }}</p>

                @if ($product->description)
                    <div class="prose prose-sm text-gray-600 mt-4 whitespace-pre-line">{{ $product->description }}</div>
                @endif
            </div>
        </div>

        {{-- Order form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-fit">
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

            @if ($product->stock_status === \App\Models\Product::STATUS_UNAVAILABLE)
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    Produk ini sedang tidak tersedia.
                </div>
            @else
                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

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
                                  placeholder="Warna, ukuran, atau permintaan khusus">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-4 py-3 transition">
                        @if ($product->isPreOrder())
                            Pre-Order via WhatsApp
                        @else
                            Pesan via WhatsApp
                        @endif
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-layouts.public>
