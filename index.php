<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAFinanceCRM - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="flex flex-col items-center mb-6">
            <svg class="w-16 h-16 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">IAFinanceCRM</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gerencie suas finanças com inteligência</p>
        </div>

        <form id="loginForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" id="email" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Senha</label>
                <input type="password" id="senha" required
                    class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div id="msgError" class="hidden text-sm text-red-600 bg-red-100 p-2 rounded"></div>

            <button type="submit"
                class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-300 transform hover:scale-[1.02]">
                Entrar
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Não tem uma conta?
                <a href="register.php" class="text-indigo-600 hover:underline font-medium">Criar conta</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const senha = document.getElementById('senha').value;
            const msgError = document.getElementById('msgError');

            try {
                const res = await fetch('api/login.php', {
                    method: 'POST',
                    body: JSON.stringify({ email, senha }),
                    headers: { 'Content-Type': 'application/json' }
                });
                const data = await res.json();

                if (res.ok) {
                    window.location.href = 'dashboard.php';
                } else {
                    msgError.textContent = data.message || 'Erro ao fazer login';
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