<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sistem Rekomendasi Laptop Mahasiswa'); ?></title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Global Reset */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F172A;
            color: #F8FAFC;
            overflow-x: hidden;
        }

        /* Modern Gradient Background */
        .bg-hero {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 15% 20%, rgba(37, 99, 235, 0.25) 0%, transparent 45%),
                        radial-gradient(circle at 85% 80%, rgba(99, 102, 241, 0.2) 0%, transparent 45%),
                        #0F172A;
            padding: 80px 0 60px;
            position: relative;
        }

        .justified-text {
            text-align: justify;
            font-size: 1.05rem; 
            line-height: 1.8; 
            color: #94A3B8; 
            font-weight: 400;
        }

        /* Modern Custom Button */
        .btn-custom {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
            color: #ffffff; 
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(37, 99, 235, 0.5);
        }

        .step-content { display: none; }
        .step-content.active { display: block; }

        /* Floating Chatbot Component Modern */
        #chatbot-widget-container {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        #chatbot-open-btn {
            width: 58px;
            height: 58px;
            padding: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563EB, #4F46E5);
            border: none;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #chatbot-open-btn:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.6);
        }

        /* Tooltip Bubble Chatbot */
        .chatbot-tooltip {
            position: relative;
            background: #1E293B;
            color: #F8FAFC;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            white-space: nowrap;
            animation: bounce 2.5s infinite;
        }

        .tooltip-arrow {
            position: absolute;
            bottom: -6px;
            right: 20px;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #1E293B;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-5px); }
            60% { transform: translateY(-2px); }
        }

        /* Window Chatbot Modal */
        #chatbot-box {
            width: 360px;
            height: 500px;
            display: none;
            flex-direction: column;
            border-radius: 20px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        #chat-messages {
            flex: 1;
            overflow-y: auto;
            background-color: #F8FAFC;
            padding: 1rem;
        }

        /* Modern Footer Styling */
        footer {
            background-color: #0B0F19 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        footer .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            background-color: #1E293B !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #F8FAFC !important;
        }

        footer .form-control:focus {
            border-color: #2563EB !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25) !important;
        }

        <?php echo $__env->yieldPushContent('styles'); ?>
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    
    <div class="bg-hero flex-grow-1">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    
    <footer class="text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5 mb-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2 text-primary">
                            <i class="bi bi-laptop-fill fs-4"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-0">SPK Laptop Mahasiswa</h5>
                    </div>
                    <p class="text-slate-400 small leading-relaxed" style="color: #94A3B8;">
                        Sistem cerdas berbasis web yang membantu para mahasiswa menemukan perangkat komputasi terbaik sesuai dengan kebutuhan perkuliahan spesifik menggunakan perhitungan matematis <strong>Weighted Product (WP)</strong>.
                    </p>
                    <p class="text-slate-400 small mt-3" style="color: #64748B;">
                        Dikembangkan oleh: <strong class="text-white">Mella</strong>
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <h6 class="text-white fw-bold mb-3 text-uppercase tracking-wider" style="font-size: 0.85rem; letter-spacing: 1px;">Kontak & Layanan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-3 d-flex align-items-center text-slate-300" style="color: #CBD5E1;">
                            <i class="bi bi-envelope-fill text-primary me-3 fs-6"></i> mellaputry04@gmail.com
                        </li>
                        <li class="mb-3 d-flex align-items-center text-slate-300" style="color: #CBD5E1;">
                            <i class="bi bi-whatsapp text-success me-3 fs-6"></i> +62 858-3200-2991
                        </li>
                        <li class="d-flex align-items-center text-slate-300" style="color: #CBD5E1;">
                            <i class="bi bi-geo-alt-fill text-danger me-3 fs-6"></i> Jurusan Sistem Informasi
                        </li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="p-4 rounded-4" style="background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255, 255, 255, 0.05);">
                        <h6 class="text-white fw-bold mb-2">Kirim Masukan Pengguna</h6>
                        <p class="text-muted small mb-3" style="font-size: 0.8rem;">Punya saran untuk pengembangan sistem ini?</p>
                        <form action="<?php echo e(route('saran.simpan')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="email" name="email" class="form-control form-control-sm mb-2" placeholder="Alamat Email Anda" required>
                            <textarea name="saran" class="form-control form-control-sm mb-3" rows="2" placeholder="Tulis masukan di sini..." required></textarea>
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold rounded-3 py-2">
                                <i class="bi bi-send-fill me-1"></i> Kirim Saran
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.08);">
            <div class="text-center text-slate-500 small" style="color: #64748B;">
                &copy; 2026 SPK Pemilihan Laptop - Mella. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    
    <script>
        function toggleChatbot() {
            const chatBox = document.getElementById('chatbot-box');
            const openBtn = document.getElementById('chatbot-open-btn');
            const tooltip = document.getElementById('chatbot-tooltip');

            if (chatBox.style.display === 'none' || chatBox.style.display === '') {
                chatBox.style.display = 'flex';
                openBtn.style.display = 'none';
                if (tooltip) tooltip.style.display = 'none';
            } else {
                chatBox.style.display = 'none';
                openBtn.style.display = 'flex';
                if (tooltip) tooltip.style.display = 'block';
            }
        }

        async function sendChatMessage() {
            const input = document.getElementById('chat-input');
            const messagesContainer = document.getElementById('chat-messages');
            const pesan = input.value.trim();

            if (!pesan) return;

            messagesContainer.innerHTML += `
                <div class="d-flex justify-content-end mb-3">
                    <div class="bg-primary text-white p-3 rounded-4 shadow-sm small text-wrap" style="max-width: 80%; border-bottom-right-radius: 4px !important;">
                        ${pesan}
                    </div>
                </div>
            `;
            
            input.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            const loadingId = 'loading-' + Date.now();
            messagesContainer.innerHTML += `
                <div id="${loadingId}" class="d-flex align-items-start mb-3">
                    <div class="bg-white text-muted p-2.5 rounded-4 shadow-sm small italic border border-light">
                        <span class="spinner-border spinner-border-sm me-1 text-primary" role="status"></span> Menjawab...
                    </div>
                </div>
            `;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                const response = await fetch("<?php echo e(route('chatbot.tanya')); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ pesan: pesan })
                });

                const data = await response.json();
                document.getElementById(loadingId).remove();

                messagesContainer.innerHTML += `
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-white p-3 rounded-4 shadow-sm border border-light text-dark small" style="max-width: 85%; border-bottom-left-radius: 4px !important;">
                            ${data.jawaban}
                        </div>
                    </div>
                `;
            } catch (error) {
                document.getElementById(loadingId).remove();
                messagesContainer.innerHTML += `
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-danger-subtle text-danger p-2.5 rounded-4 small">
                            Gagal terhubung ke server.
                        </div>
                    </div>
                `;
            }

            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        const chatInputEl = document.getElementById('chat-input');
        if (chatInputEl) {
            chatInputEl.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') sendChatMessage();
            });
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\SPK-Pemilihan-Laptop-Berbasis-Laravel-main\resources\views/Front/layouts/main.blade.php ENDPATH**/ ?>