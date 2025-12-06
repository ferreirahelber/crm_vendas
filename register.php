<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAFinanceCRM - Registrar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="flex flex-col items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Crie sua Conta</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Comece a controlar suas finanças hoje</p>
        </div>

        <form id="registerForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Completo</label>
                <input type="text" id="nome" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" id="email" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Senha</label>
                <input type="password" id="senha" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div id="msgError" class="hidden text-sm text-red-600 bg-red-100 p-2 rounded"></div>
            <div id="msgSuccess" class="hidden text-sm text-green-600 bg-green-100 p-2 rounded"></div>

            <button type="submit"
                class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition duration-300 transform hover:scale-[1.02]">
                Criar Conta
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Já tem uma conta?
                <a href="index.php" class="text-green-600 hover:underline font-medium">Fazer Login</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const nome = document.getElementById('nome').value;
            const email = document.getElementById('email').value;
            const senha = document.getElementById('senha').value;
            const msgError = document.getElementById('msgError');
            const msgSuccess = document.getElementById('msgSuccess');

            msgError.classList.add('hidden');
            msgSuccess.classList.add('hidden');

            try {
                const res = await fetch('api/register.php', {
                    method: 'POST',
                    body: JSON.stringify({ nome, email, senha }),
                    headers: { 'Content-Type': 'application/json' }
                });
                const data = await res.json();

                if (res.ok) {
                    msgSuccess.textContent = 'Conta criada com sucesso! Redirecionando...';
                    msgSuccess.classList.remove('hidden');
                    setTimeout(() => window.location.href = 'index.php', 2000);
                } else {
                    msgError.textContent = data.message || 'Erro ao criar conta';
                    msgError.classList.remove('hidden');
                }
            } catch (err) {
                console.error(err);
                msgError.textContent = 'Erro de conexão';
                msgError.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>