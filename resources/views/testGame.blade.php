<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            API Test
        </h2>
    </x-slot>
    <div class="p-6">


        <form id="word-form" class="flex flex-col gap-2 bg-white p-4 rounded shadow mt-8">
            <label>
                Word to guess:
                <input type="text" name="guess" class="border rounded px-2 py-1" maxlength="5" required>
            </label>
            <input type="hidden" name="token" id="word-token">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Check Word (POST /api/words/check)</button>
        </form>
        <div id="word-result" class="mt-4 bg-gray-100 p-4 rounded text-xs font-mono"></div>
    </div>
    <script>
        // Generate a random token and set it in the hidden input
        function generateToken(length = 32) {
            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let token = '';
            for (let i = 0; i < length; i++) {
                token += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return token;
        }
        document.getElementById('word-token').value = generateToken();

        // Handle word check form
        document.getElementById('word-form').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const res = await fetch('/api/words/check', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            const resultDiv = document.getElementById('word-result');
            if (data.result) {
                resultDiv.innerHTML = `
                    <div class="flex gap-2 mb-2">
                        ${data.result.map(l =>
                            `<span class="px-3 py-2 rounded font-bold text-white"
                                style="background:${l.color === 'green' ? '#22c55e' : l.color === 'yellow' ? '#eab308' : '#222'};">
                                ${l.letter}
                            </span>`
                        ).join('')}
                    </div>
                    <div class="text-sm text-gray-700">Tries left: <span class="font-bold">${data.tries_left}</span></div>
                `;
            } else if (data.errors) {
                resultDiv.innerHTML = `<div class="text-red-600">${Object.values(data.errors).join('<br>')}</div>`;
            } else {
                resultDiv.textContent = JSON.stringify(data, null, 2);
            }
        };

        document.getElementById('load-icons').onclick = function() {
            fetch('/api/icons')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('result').textContent = JSON.stringify(data, null, 2);
                });
        };

        document.getElementById('upload-form').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const res = await fetch('/api/icons', {
                method: 'POST',

                body: formData
            });
            const data = await res.json();
            document.getElementById('upload-result').textContent = JSON.stringify(data, null, 2);
        };
    </script>
</x-app-layout>