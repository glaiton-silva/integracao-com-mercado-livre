<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold text-gray-800">Detalhes do Produto</h1>

        <div class="mt-6 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-gray-800">{{ $product->name }}</h3>
                <p class="text-sm text-gray-600"><strong>Descrição:</strong> {{ $product->description }}</p>
                <p class="text-sm text-gray-600"><strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                <p class="text-sm text-gray-600"><strong>Quantidade em Estoque:</strong> {{ $product->quantity }}</p>
                <p class="text-sm text-gray-600"><strong>Categoria:</strong> {{ $product->category }}</p>
            </div>
            @if($product->images)
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h4 class="text-xl font-medium text-gray-800">Imagem do Produto</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                        <div class="relative">
                            @if($product->images)
                                @php
                                    $images = json_decode($product->images, true) ?? [];
                                @endphp
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700">Imagem Cadastrada</label>
                                    <div class="mt-2">
                                        @foreach($images as $image)
                                            <img src="{{ asset('storage/' . $image) }}" alt="Imagem do Produto" class="h-32 object-cover rounded-md">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500">Nenhuma imagem cadastrada para este produto.</p>
            @endif

            <div class="mt-6 flex space-x-4">
                <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Voltar para a lista
                </a>
                <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    Editar Produto
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
