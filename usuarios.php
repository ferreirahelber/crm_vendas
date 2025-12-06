<?php

include 'src/auth.php';
checkAuth();

if (!isAdmin()) {
    echo "Acesso Negado.";
    exit;
}

include 'src/layout_header.php';
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gerenciar Usuários</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Apenas para administradores</p>
        </div>
    </div>

    <!-- Lista Desktop (Tabela) -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                <tr>
                    <th class="p-4 font-medium">ID</th>
                    <th class="p-4 font-medium">Nome</th>
                    <th class="p-4 font-medium">Email</th>
                    <th class="p-4 font-medium">Role</th>
                    <th class="p-4 font-medium">Criado em</th>
                    <th class="p-4 font-medium text-right">Ações</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-gray-100 dark:divide-gray-700">
                <!-- Preenchido via JS -->
            </tbody>
        </table>
    </div>
</div>

<script>
    const API_LIST = 'api/list_usuarios.php';
    const API_DELETE = 'api/delete_usuario.php';

    async function loadItems() {
        try {
            const res = await fetch(API_LIST);
            const users = await res.json();
            render(users);
        } catch (e) { console.error(e); }
    }

    function render(users) {
        const tbody = document.getElementById('table-body');
        tbody.innerHTML = '';

        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="p-4 text-center">Nenhum usuário encontrado.</td></tr>';
            return;
        }

        users.forEach(user => {
            const created = new Date(user.created_at).toLocaleDateString('pt-BR');
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50 transition';
            tr.innerHTML = `
                <td class="p-4 text-sm dark:text-gray-300">#${user.id}</td>
                <td class="p-4 text-sm font-medium dark:text-white">${user.nome}</td>
                <td class="p-4 text-sm dark:text-gray-300">${user.email}</td>
                <td class="p-4 text-sm"><span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs uppercase font-bold">${user.role}</span></td>
                <td class="p-4 text-sm dark:text-gray-300">${created}</td>
                <td class="p-4 text-right">
                    <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm font-semibold">Remover</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    window.deleteUser = async function (id) {
        if (!confirm('Tem certeza que deseja remover este usuário? Esta ação é irreversível.')) return;

        try {
            const res = await fetch(API_DELETE, {
                method: 'POST',
                body: JSON.stringify({ id }),
                headers: { 'Content-Type': 'application/json' }
            });
            const data = await res.json();

            if (res.ok) {
                alert(data.message);
                loadItems();
            } else {
                alert(data.message);
            }
        } catch (e) {
            alert('Erro de conexão');
        }
    }

    loadItems();
</script>

<?php include 'src/layout_bottom.php'; ?>