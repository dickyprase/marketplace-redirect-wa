<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Banner</h2>
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">+ Tambah Banner</a>
        </div>
    </x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))<div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">{{ session('success') }}</div>@endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Link</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktif</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($banners as $b)
                        <tr>
                            <td class="px-4 py-3"><img src="{{ asset('storage/'.$b->image_path) }}" class="w-32 h-12 object-cover rounded" alt=""></td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $b->link ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $b->sort_order }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block text-xs px-2 py-1 rounded-full {{ $b->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.banners.edit', $b) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                                <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Hapus banner ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Belum ada banner.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $banners->links() }}</div>
    </div></div>
</x-app-layout>
