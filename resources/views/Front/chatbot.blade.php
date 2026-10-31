<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot SPK Laptop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col justify-between">

    <!-- Header Chatbot -->
    <header class="bg-blue-600 text-white p-4 font-bold text-lg flex items-center justify-between shadow">
        <span>Asisten SPK Pemilihan Laptop</span>
        <a href="{{ url('/') }}" class="text-sm bg-blue-700 hover:bg-blue-800 px-3 py-1 rounded">Kembali</a>
    </header>

    <!-- Container Pesan -->
    <main id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-4">
        <div class="flex items-start gap-2">
            <div class="bg-white p-3 rounded-lg shadow text-gray-800 max-w-lg">
                Halo! Saya asisten virtual SPK Laptop. Ada yang bisa saya bantu mengenai kriteria WP atau rekomendasi laptop?
            </div>
        </div>
    </main>

    <!-- Input Form -->
    <footer class="p-4 bg-white border-t flex gap-2">
        <input type="text" id="pesan-input" placeholder="Tanyakan sesuatu..." class="flex-1 border border-gray-300 p-2 rounded-lg focus:outline-none focus:border-blue-500" autocomplete="off">
        <button id="btn-kirim" onclick="kirimPesan()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">
            Kirim
        </button>
    </footer>

    <!-- Script Pengiriman Pesan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('pesan-input');
            const chatBox = document.getElementById('chat-box');
            const btnKirim = document.getElementById('btn-kirim');

            window.kirimPesan = function() {
                const pesan = input.value.trim();

                if (!pesan) return;

                // 1. Tampilkan pesan user ke layar sebelah kanan
                chatBox.insertAdjacentHTML('beforeend', `
                    <div class="flex items-end justify-end gap-2">
                        <div class="bg-blue-600 text-white p-3 rounded-lg shadow max-w-lg">
                            ${pesan}
                        </div>
                    </div>
                `);
                
                input.value = '';
                chatBox.scrollTop = chatBox.scrollHeight;

                // 2. Kunci input sementara & tampilkan loading
                btnKirim.disabled = true;
                const loadingId = 'loading-' + Date.now();
                chatBox.insertAdjacentHTML('beforeend', `
                    <div id="${loadingId}" class="flex items-start gap-2">
                        <div class="bg-gray-200 text-gray-600 p-3 rounded-lg shadow italic flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sedang memproses...
                        </div>
                    </div>
                `);
                chatBox.scrollTop = chatBox.scrollHeight;

                // 3. Kirim via Fetch AJAX ke Controller Laravel (Menggunakan URL Absolut)
                fetch("{{ url('/chatbot/tanya') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ pesan: pesan })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("HTTP Error Status: " + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    const loadingEl = document.getElementById(loadingId);
                    if (loadingEl) loadingEl.remove();
                    btnKirim.disabled = false;

                    // 4. Tampilkan balasan AI
                    chatBox.insertAdjacentHTML('beforeend', `
                        <div class="flex items-start gap-2">
                            <div class="bg-white p-3 rounded-lg shadow text-gray-800 max-w-lg leading-relaxed">
                                ${data.jawaban}
                            </div>
                        </div>
                    `);
                    chatBox.scrollTop = chatBox.scrollHeight;
                })
                .catch(error => {
                    const loadingEl = document.getElementById(loadingId);
                    if (loadingEl) loadingEl.remove();
                    btnKirim.disabled = false;

                    console.error("Detail Error:", error);
                    chatBox.insertAdjacentHTML('beforeend', `
                        <div class="flex items-start gap-2">
                            <div class="bg-red-100 text-red-700 p-3 rounded-lg shadow max-w-lg">
                                <strong>Terjadi Kendala:</strong> ${error.message}
                            </div>
                        </div>
                    `);
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
            };

            // Jalankan fungsi kirim via tombol Enter
            input.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') kirimPesan();
            });
        });
    </script>

</body>
</html>