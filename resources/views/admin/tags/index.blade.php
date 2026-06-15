<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Tag (Promo)</h2>
            <a href="{{ route('admin.tags.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md">+ Tambah Tag</a>
        </div>
    </x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))<div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">{{ session('success') }}</div>@endif

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tags as $tag)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $tag->name }}</td>
                            <td class="px-4 py-3 text-gray-500 text-sm">{{ $tag->slug }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.tags.edit', $tag) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                                <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Hapus tag ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-10 text-center text-gray-400">Belum ada tag.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $tags->links() }}</div>
    </div></div>
</x-app-layout>
