<?php

include 'src/auth.php';
checkAuth();
include 'src/layout_header.php';
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Despesas</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Controle seus gastos com cuidado</p>
        </div>
        <button onclick="openModal()"
            class="w-full md:w-auto px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center justify-center gap-2 transition shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nova Despesa
        </button>
    </div>

    <!-- Filtros -->
    <div
        class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-wrap gap-4">
        <input type="date" id="filter-start"
            class="px-3 py-2 border rounded-lg text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        <input type="date" id="filter-end"
            class="px-3 py-2 border rounded-lg text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        <select id="filter-cat"
            class="px-3 py-2 border rounded-lg text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Todas Categorias</option>
            <option value="Alimentação">Alimentação</option>
            <option value="Transporte">Transporte</option>
            <option value="Moradia">Moradia</option>
            <option value="Lazer">Lazer</option>
            <option value="Outros">Outros</option>
        </select>
        <button onclick="loadItems()"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition">Filtrar</button>
    </div>

    <!-- Lista Desktop (Tabela) -->
    <div
        class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="p-4 font-medium">Data</th>
                    <th class="p-4 font-medium">Descrição</th>
                    <th class="p-4 font-medium">Categoria</th>
                    <th class="p-4 font-medium">Valor</th>
                    <th class="p-4 font-medium text-right">Ações</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-gray-100 dark:divide-gray-700">
                <!-- Preenchido via JS -->
            </tbody>
        </table>
    </div>

    <!-- Lista Mobile (Cards) -->
    <div class="md:hidden space-y-3" id="mobile-list">
        <!-- Preenchido via JS -->
    </div>
</div>

<!-- Modal Create/Edit -->
<div id="modal-form"
    class="fixed inset-0 bg-black bg-opacity-50 z-[70] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl p-6 shadow-2xl transform transition-all">
        <h3 class="text-xl font-bold dark:text-white mb-4" id="modal-title">Nova Despesa</h3>
        <form id="formRec" class="space-y-4">
            <input type="hidden" id="rec-id">
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <input type="text" id="rec-desc" required
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Valor</label>
                    <input type="number" step="0.01" id="rec-valor" required
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Data</label>
                    <input type="date" id="rec-data" required
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                <select id="rec-cat"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="Alimentação">Alimentação</option>
                    <option value="Transporte">Transporte</option>
                    <option value="Moradia">Moradia</option>
                    <option value="Lazer">Lazer</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">Cancelar</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const API_LIST = 'api/list_despesas.php';
    const API_CREATE = 'api/create_despesa.php';
    const API_UPDATE = 'api/update_despesa.php';
    const API_DELETE = 'api/delete_despesa.php';

    let currentItems = [];

    async function loadItems() {
        const start = document.getElementById('filter-start').value;
        const end = document.getElementById('filter-end').value;
        const cat = document.getElementById('filter-cat').value;

        let url = `${API_LIST}?`;
        if (start) url += `start_date=${start}&`;
        if (end) url += `end_date=${end}&`;
        if (cat) url += `categoria=${cat}&`;

        try {
            const res = await fetch(url);
            currentItems = await res.json();
            render();
        } catch (e) { console.error(e); }
    }

    function render() {
        const tbody = document.getElementById('table-body');
        const mobileList = document.getElementById('mobile-list');
        tbody.innerHTML = '';
        mobileList.innerHTML = '';

        if (currentItems.length === 0) {
            mobileList.innerHTML = '<div class="text-center text-gray-500 py-8">Nenhuma despesa encontrada.</div>';
            return;
        }

        currentItems.forEach(item => {
            const valorFmt = Number(item.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            const dataFmt = new Date(item.data).toLocaleDateString('pt-BR');

            // Table Row
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50 transition';
            tr.innerHTML = `
                <td class="p-4 text-sm text-gray-600 dark:text-gray-300">${dataFmt}</td>
                <td class="p-4 text-sm font-medium text-gray-800 dark:text-white">${item.descricao}</td>
                <td class="p-4 text-sm"><span class="px-2 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 rounded text-xs font-semibold">${item.categoria}</span></td>
                <td class="p-4 text-sm font-bold text-red-600 dark:text-red-400">${valorFmt}</td>
                <td class="p-4 text-right space-x-2">
                    <button onclick='editItem(${JSON.stringify(item)})' class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Editar</button>
                    <button onclick="deleteItem(${item.id})" class="text-red-600 hover:text-red-800 dark:text-red-400">Excluir</button>
                </td>
            `;
            tbody.appendChild(tr);

            // Mobile Card
            const card = document.createElement('div');
            card.className = 'bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center';
            card.innerHTML = `
                <div>
                    <p class="font-bold text-gray-800 dark:text-white">${item.descricao}</p>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                        <span>${dataFmt}</span>
                        <span>•</span>
                        <span class="text-red-600 dark:text-red-400">${item.categoria}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-red-600 dark:text-red-400">${valorFmt}</p>
                    <div class="flex gap-3 justify-end mt-2">
                        <button onclick='editItem(${JSON.stringify(item)})' class="text-indigo-600 text-xs">Editar</button>
                        <button onclick="deleteItem(${item.id})" class="text-red-600 text-xs">Excluir</button>
                    </div>
                </div>
            `;
            mobileList.appendChild(card);
        });
    }

    // Modal & Form Handling
    const modal = document.getElementById('modal-form');
    function openModal() {
        document.getElementById('formRec').reset();
        document.getElementById('rec-id').value = '';
        document.getElementById('modal-title').innerText = 'Nova Despesa';
        document.getElementById('rec-data').valueAsDate = new Date();
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    window.editItem = function (item) {
        document.getElementById('rec-id').value = item.id;
        document.getElementById('rec-desc').value = item.descricao;
        document.getElementById('rec-valor').value = item.valor;
        document.getElementById('rec-data').value = item.data;
        document.getElementById('rec-cat').value = item.categoria;
        document.getElementById('modal-title').innerText = 'Editar Despesa';
        modal.classList.remove('hidden');
    }

    window.deleteItem = async function (id) {
        if (!confirm('Tem certeza que deseja excluir?')) return;
        await fetch(API_DELETE, {
            method: 'POST',
            body: JSON.stringify({ id }),
            headers: { 'Content-Type': 'application/json' }
        });
        loadItems();
    }

    document.getElementById('formRec').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('rec-id').value;
        const payload = {
            descricao: document.getElementById('rec-desc').value,
            valor: document.getElementById('rec-valor').value,
            data: document.getElementById('rec-data').value,
            categoria: document.getElementById('rec-cat').value
        };

        let url = API_CREATE;
        if (id) {
            url = API_UPDATE;
            payload.id = id;
        }

        const res = await fetch(url, {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: { 'Content-Type': 'application/json' }
        });

        if (res.ok) {
            closeModal();
            loadItems();
        } else {
            alert('Erro ao salvar');
        }
    });

    // Auto load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'new') {
        openModal();
    }
    loadItems();
</script>

<?php include 'src/layout_bottom.php'; ?>