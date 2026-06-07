<x-layouts.public :title="config('app.name')">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Katalog Produk</h1>
        <p class="text-gray-500 mt-1">Pilih produk dan pesan langsung melalui WhatsApp.</p>
    </div>

    @if ($products->isEmpty())
        <div class="text-center text-gray-400 py-20">
            Belum ada produk yang tersedia.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product) }}"
                   class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 flex flex-col">
                    <div class="aspect-video bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                 alt="{{ $product->name }}" class="object-cover w-full h-full">
                        @else
                            <span class="text-gray-300 text-sm">Tanpa Gambar</span>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <h2 class="font-semibold text-gray-800 leading-snug">{{ $product->name }}</h2>
                            <x-stock-badge :status="$product->stock_status" />
                        </div>
                        <p class="text-indigo-600 font-bold mt-3 text-lg">{{ $product->formatted_price }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-layouts.public>
