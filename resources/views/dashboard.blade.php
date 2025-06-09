<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @php
                $icon = \App\Models\Icon::find(5);
                @endphp
                @if($icon)
                <img src="{{ asset('storage/' . $icon->image) }}" alt="{{ $icon->name }}" class="w-8 h-8 inline" title="{{ $icon->name }}">
                @endif
            </div>
        </div>
    </div>
</x-app-layout>