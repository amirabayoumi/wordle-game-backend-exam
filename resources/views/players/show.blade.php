<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Player #{{ $player->id }} ({{ $player->token }}) - {{ $player->date }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Tries</h3>
                    <ol class="list-decimal pl-6">
                        @for($i=1; $i<=6; $i++)
                            @if($player->{'try'.$i})
                            <li class="mb-2">
                                <span class="inline-block bg-gray-100 rounded px-2 py-1 text-sm">{{ $player->{'try'.$i} }}</span>
                            </li>
                            @endif
                            @endfor
                    </ol>
                    <div class="mt-6">
                        <a href="{{ route('players.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            &larr; Back to Players List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>