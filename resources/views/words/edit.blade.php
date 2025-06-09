<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Word') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('words.index') }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Back to words list
                        </a>
                    </div>

                    @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('words.update', $word) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="word" class="block text-sm font-medium text-gray-700">Word</label>
                            <input type="text" name="word" id="word" value="{{ old('word', $word->word) }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Maximum 5 characters</p>
                        </div>

                        <div class="mb-4">
                            <label for="schedule_at" class="block text-sm font-medium text-gray-700">Schedule Date</label>
                            <input type="date" name="schedule_at" id="schedule_at"
                                value="{{ old('schedule_at', $word->schedule_at->format('Y-m-d')) }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-blue-700">
                                Update Word
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>