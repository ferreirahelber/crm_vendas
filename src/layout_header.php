<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>CRM Vendas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5', // Indigo 600
                        secondary: '#10B981', // Emerald 500
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#4F46E5">
    <link rel="apple-touch-icon" href="assets/icons/icon-192.png">

    <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1f2937;
        }

        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased pb-20 md:pb-0">

    <nav
        class="hidden md:flex bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 px-6 py-4 justify-between items-center sticky top-0 z-50">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">CV</div>
            <span class="text-xl font-bold tracking-tight">CRM Vendas</span>
        </div>
        <div class="flex items-center space-x-6">
            <a href="dashboard.php" class="hover:text-indigo-500 transition">Dashboard</a>
            <a href="receitas.php" class="hover:text-indigo-500 transition">Receitas</a>
            <a href="despesas.php" class="hover:text-indigo-500 transition">Despesas</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="usuarios.php" class="hover:text-indigo-500 transition">Usuários</a>
            <?php endif; ?>
            <button onclick="toggleTheme()"
                class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                🌗
            </button>
            <a href="logout.php" class="text-red-500 hover:text-red-700 font-medium">Sair</a>
        </div>
    </nav>

    <div class="md:hidden flex bg-white dark:bg-gray-800 shadow-sm p-4 justify-between items-center sticky top-0 z-50">
        <span class="text-lg font-bold">CRM Vendas</span>
        <div class="flex space-x-3">
            <button onclick="toggleTheme()" class="text-xl">🌗</button>
            <a href="logout.php" class="text-red-500 font-medium">Sair</a>
        </div>
    </div>

    <main class="max-w-7xl mx-auto p-4 md:p-6 lg:p-8 min-h-[calc(100vh-80px)]"></main>