<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Infinite Whiteboard') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Konva.js via CDN -->
    <script src="https://unpkg.com/konva@9/konva.min.js"></script>

    <!-- Lucide Icons via CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-900 antialiased flex flex-col">
    <!-- Main Content -->
    <main class="flex-1 flex flex-col relative overflow-hidden">
        @yield('content')
    </main>

    <!-- Global Notifications / Toasts -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none">
        @if (session('success'))
            <div class="toast-item pointer-events-auto flex items-center gap-3 bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg transform transition-all duration-300 translate-y-0 opacity-100 max-w-sm">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-200 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="toast-item pointer-events-auto flex items-center gap-3 bg-rose-600 text-white px-4 py-3 rounded-xl shadow-lg transform transition-all duration-300 translate-y-0 opacity-100 max-w-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="text-sm font-medium">{{ $error }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-rose-200 hover:text-white transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endforeach
        @endif
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        // Helper function to show notifications dynamically from JS
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgClass = type === 'success' ? 'bg-emerald-600' : 'bg-rose-600';
            const iconName = type === 'success' ? 'check-circle' : 'alert-circle';
            const textClass = type === 'success' ? 'text-emerald-200' : 'text-rose-200';

            toast.className = `toast-item pointer-events-auto flex items-center gap-3 ${bgClass} text-white px-4 py-3 rounded-xl shadow-lg transform transition-all duration-300 translate-y-5 opacity-0 max-w-sm`;
            toast.innerHTML = `
                <i data-lucide="${iconName}" class="w-5 h-5 flex-shrink-0"></i>
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto ${textClass} hover:text-white transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;
            
            container.appendChild(toast);
            lucide.createIcons({attrs: {class: 'w-5 h-5'}});
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-y-5', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-5', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>
