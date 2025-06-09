<x-app-layout>
    <x-slot name="header">

        @php
        $icon = \App\Models\Icon::find(4);
        @endphp
        @if($icon)
        <div class="flex items-center gap-2 mb-4">

            <img src="{{ asset('storage/' . $icon->image) }}" alt="{{ $icon->name }}" class="w-4 inline object-contain " title="{{ $icon->name }} ">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Words') }}
            </h2>
        </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4 ">
                        <a href="{{ route('words.create') }}" class=" px-4 py-2  border  rounded-md font-semibold  uppercase tracking-widest ">
                            Add New Word
                        </a>
                    </div>

                    @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Word
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Schedule At
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($words as $word)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $word->word }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $word->schedule_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <a href="{{ route('words.show', $word) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-2">
                                        View
                                    </a>
                                    <a href="{{ route('words.edit', $word) }}" class="text-green-600 hover:text-green-900 font-medium mr-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('words.destroy', $word) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium"
                                            onclick="return confirm('Are you sure you want to delete this word?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No words found. Add some words to get started.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>