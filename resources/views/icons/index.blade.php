<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Icons') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <!-- Inline upload form -->
        <form action="{{ route('icons.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 flex flex-col md:flex-row items-center gap-4 bg-white p-4 rounded shadow">
            @csrf
            <input type="text" name="name" placeholder="Icon name" class="border rounded px-3 py-2 text-black" required>
            <input type="file" name="image" accept="image/*" class="border rounded px-3 py-2 text-black" required>
            <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded">Upload</button>
            @if ($errors->any())
            <div class="text-red-500 text-sm mt-2">
                {{ $errors->first() }}
            </div>
            @endif
        </form>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($icons as $icon)
            <div class="bg-white p-4 rounded shadow flex flex-col items-center">
                <img src="{{ asset('storage/'.$icon->image) }}" alt="{{ $icon->name }}" class="w-16 h-16 object-contain mb-2">
                <div class="text-center text-black mb-2">{{ $icon->name }}</div>
                <form action="{{ route('icons.destroy', $icon) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this icon?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>