<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold text-gray-800">Lista de Produtos</h1>

        <div class="mt-6 mb-8 flex justify-between items-center">
            <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-200 ease-in-out">
                Adicionar Novo Produto
            </a>
        </div>

        @if($products->isEmpty())
            <div class="bg-yellow-100 p-4 rounded-lg shadow-md text-yellow-700">
                <p class="font-medium">Ainda não há produtos cadastrados.</p>
                <p>Comece adicionando novos produtos à sua lista!</p>
            </div>
        @else
            <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
                <table class="min-w-full table-auto border-separate border-spacing-0">
                    <thead class="bg-gray-200 text-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left">Nome</th>
                            <th class="px-6 py-3 text-left">Preço</th>
                            <th class="px-6 py-3 text-left">Quantidade</th>
                            <th class="px-6 py-3 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr class="border-t border-gray-200">
                                <td class="px-6 py-4 text-sm">{{ $product->name }}</td>
                                <td class="px-6 py-4 text-sm">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $product->quantity }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('products.show', $product) }}" class="text-blue-500 hover:text-blue-700 transition duration-200 ease-in-out">Ver</a> |
                                    <a href="{{ route('products.edit', $product) }}" class="text-yellow-500 hover:text-yellow-700 transition duration-200 ease-in-out">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
