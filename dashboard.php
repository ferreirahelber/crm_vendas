<?php
include 'src/auth.php';
checkAuth();
include 'src/layout_header.php';
?>
<div class="space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Olá, <span id="user-name"><?php echo htmlspecialchars($_SESSION['nome'] ?? 'Visitante'); ?></span> 👋
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Aqui está o resumo financeiro.</p>
        </div>
        <div id="status-badge" class="hidden px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm">
            </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-50 group-hover:opacity-20 transition">
                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Receitas</p>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mt-1" id="total-receitas">R$ ...</h2>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-50 group-hover:opacity-20 transition">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Despesas</p>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mt-1" id="total-despesas">R$ ...</h2>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-50 group-hover:opacity-20 transition">
                <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Balanço Atual</p>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white mt-1" id="total-saldo">R$ ...</h2>
        </div>
    </div>

    <div class="w-full bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Fluxo de Caixa (6 Meses)</h3>
        <div class="relative h-72 w-full">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    <div class="w-full bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Últimos Lançamentos</h3>
            <a href="receitas.php" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ver extrato completo</a>
        </div>
        <div id="recent-list" class="space-y-3">
            <div class="animate-pulse space-y-4">
                <div class="h-12 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                <div class="h-12 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    async function loadDashboard() {
        try {
            const res = await fetch('api/dashboard_stats.php');
            const data = await res.json();

            const formatMoney = (val) => Number(val).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

            document.getElementById('total-receitas').textContent = formatMoney(data.totais.receitas);
            document.getElementById('total-despesas').textContent = formatMoney(data.totais.despesas);
            document.getElementById('total-saldo').textContent = formatMoney(data.totais.saldo);

            const badge = document.getElementById('status-badge');
            if (data.totais.saldo >= 0) {
                badge.textContent = "Empresa Positiva";
                badge.classList.add('bg-green-100', 'text-green-700', 'dark:bg-green-900', 'dark:text-green-300');
            } else {
                badge.textContent = "No Vermelho";
                badge.classList.add('bg-red-100', 'text-red-700', 'dark:bg-red-900', 'dark:text-red-300');
            }
            badge.classList.remove('hidden');

            const list = document.getElementById('recent-list');
            list.innerHTML = '';
            if (data.recentes.length === 0) {
                list.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum lançamento recente.</p>';
            } else {
                data.recentes.forEach(item => {
                    const isReceita = item.tipo === 'receita';
                    const colorClass = isReceita ? 'text-green-600 bg-green-100 dark:bg-green-900/30' : 'text-red-600 bg-red-100 dark:bg-red-900/30';
                    const icon = isReceita ? '+' : '-';
                    const dateDesc = new Date(item.data).toLocaleDateString('pt-BR');

                    const el = document.createElement('div');
                    el.className = 'flex flex-col md:flex-row md:items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-700/30 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition border border-gray-100 dark:border-gray-700';
                    el.innerHTML = `
                    <div class="flex items-center gap-4 mb-2 md:mb-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg ${colorClass} shrink-0">${icon}</div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-lg">${item.descricao}</p>
                            <p class="text-sm text-gray-500 capitalize">${item.categoria} • ${dateDesc}</p>
                        </div>
                    </div>
                    <span class="font-bold text-lg ${isReceita ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">
                        ${isReceita ? '+' : '-'} ${formatMoney(item.valor)}
                    </span>`;
                    list.appendChild(el);
                });
            }

            const ctx = document.getElementById('financeChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.chart.labels,
                    datasets: [
                        { label: 'Receitas', data: data.chart.receitas, backgroundColor: '#10B981', borderRadius: 4 },
                        { label: 'Despesas', data: data.chart.despesas, backgroundColor: '#EF4444', borderRadius: 4 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { color: document.documentElement.className.includes('dark') ? '#ccc' : '#666' } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: document.documentElement.className.includes('dark') ? '#374151' : '#e5e7eb' }, ticks: { color: document.documentElement.className.includes('dark') ? '#9ca3af' : '#4b5563' } },
                        x: { grid: { display: false }, ticks: { color: document.documentElement.className.includes('dark') ? '#9ca3af' : '#4b5563' } }
                    }
                }
            });
        } catch (err) { console.error(err); }
    }
    loadDashboard();
</script>

<?php include 'src/layout_bottom.php'; ?>