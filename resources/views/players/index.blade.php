<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Players') }}
        </h2>
    </x-slot>

    <div class="flex flex-row gap-6 px-2 mt-6 mb-10 overflow-x-auto">
        <!-- Today's Top Words -->
        <div class="min-w-[320px] flex-1 bg-white shadow-lg rounded-2xl p-6 border border-indigo-100">
            <h3 class="text-lg font-semibold mb-4 text-indigo-700">Top 10 Most Guessed Words (Today)</h3>
            <div class="grid grid-cols-2 gap-4">
                @forelse($todayTries as $word => $count)
                <div class="flex items-center justify-between bg-indigo-50 rounded-xl px-4 py-3 shadow-sm">
                    <span class="font-mono font-bold text-indigo-800 text-lg">{{ $word }}</span>
                    <span class="bg-indigo-200 text-indigo-900 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $count }}x</span>
                </div>
                @empty
                <div class="col-span-2 text-center text-gray-400">No guesses yet for today.</div>
                @endforelse
            </div>
        </div>
        <!-- All Time Top Words -->
        <div class="min-w-[320px] flex-1 bg-white shadow-lg rounded-2xl p-6 border border-green-100">
            <h3 class="text-lg font-semibold mb-4 text-green-700">Top 10 Most Guessed Words (All Time)</h3>
            <div class="grid grid-cols-2 gap-4">
                @forelse($tries as $word => $count)
                <div class="flex items-center justify-between bg-green-50 rounded-xl px-4 py-3 shadow-sm">
                    <span class="font-mono font-bold text-green-800 text-lg">{{ $word }}</span>
                    <span class="bg-green-200 text-green-900 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $count }}x</span>
                </div>
                @empty
                <div class="col-span-2 text-center text-gray-400">No guesses yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Token
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tries
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($players as $player)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $player->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $player->token }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $player->date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @for($i=1; $i<=6; $i++)
                                        @if($player->{'try'.$i})
                                        <span class="inline-block bg-gray-100 rounded px-2 py-1 text-xs mr-1">{{ $player->{'try'.$i} }}</span>
                                        @endif
                                        @endfor
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <a href="{{ route('players.show', $player->id) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-2">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    No players found.
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