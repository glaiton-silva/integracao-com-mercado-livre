<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Total de Produtos</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Produtos cadastrados no sistema.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Usuários Ativos</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalActiveUsers }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Usuários logados no sistema.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Vendas Hoje</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalSalesToday }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Vendas registradas no dia.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
