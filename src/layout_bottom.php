</main>
<!-- Bottom Navigation (Mobile Only) -->
<nav
    class="md:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-around items-center py-3 pb-safe z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
    <a href="dashboard.php"
        class="flex flex-col items-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition nav-item"
        id="nav-dash">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
            </path>
        </svg>
        <span class="text-xs font-medium">Dash</span>
    </a>
    <a href="receitas.php"
        class="flex flex-col items-center text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition nav-item"
        id="nav-rec">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
        </svg>
        <span class="text-xs font-medium">Receitas</span>
    </a>

    <!-- Floating Action Button (Center) -->
    <div class="relative -top-5">
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
            class="w-14 h-14 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-indigo-500/50 hover:bg-indigo-700 transition transform hover:scale-105">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
    </div>

    <a href="despesas.php"
        class="flex flex-col items-center text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition nav-item"
        id="nav-desp">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
        </svg>
        <span class="text-xs font-medium">Despesas</span>
    </a>
    <a href="#" onclick="alert('Funcionalidade de Perfil em breve!')"
        class="flex flex-col items-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition nav-item">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <span class="text-xs font-medium">Perfil</span>
    </a>
</nav>

<!-- Modal Genérico de Adição Rápida (Exemplo de uso) -->
<div id="modal-add"
    class="fixed inset-0 bg-black bg-opacity-50 z-[60] hidden flex items-end sm:items-center justify-center p-4 backdrop-blur-sm">
    <div
        class="bg-white dark:bg-gray-800 w-full max-w-sm rounded-t-2xl sm:rounded-2xl p-6 transform transition-all animate-slide-up sm:animate-fade-in shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold dark:text-white">Novo Lançamento</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
        </div>
        <div class="space-y-3">
            <a href="receitas.php?action=new"
                class="block w-full py-4 px-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-xl flex items-center space-x-3 hover:bg-green-200 dark:hover:bg-green-900/50 transition">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <span class="font-semibold text-lg">Nova Receita</span>
            </a>
            <a href="despesas.php?action=new"
                class="block w-full py-4 px-4 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-xl flex items-center space-x-3 hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
                <span class="font-semibold text-lg">Nova Despesa</span>
            </a>
        </div>
    </div>
</div>

<script>
    // Theme Toggle Logic
    const html = document.documentElement;
    function toggleTheme() {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Check local storage
    if (localStorage.getItem('theme') === 'light') {
        html.classList.remove('dark'); // Default is dark in html tag, remove if light matches
    }

    // Register PWA Service Worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('service-worker.js')
            .then(() => console.log('SW Registered'))
            .catch(console.error);
    }
</script>

</body>

</html>