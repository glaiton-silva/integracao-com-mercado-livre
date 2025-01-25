<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold text-gray-800">Editar Produto</h1>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Nome -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Descrição -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
                @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Preço -->
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700">Preço</label>
                <input type="text" name="price" id="price" value="{{ old('price', $product->price) }}" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('price') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Quantidade -->
            <div class="mb-6">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantidade</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('quantity') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Categoria -->
            <div class="mb-6">
                <label for="categories" class="block text-sm font-medium text-gray-700">Categoria</label>
                <select name="categories" id="categories" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category['ml_category_id'] }}" {{ $product->category == $category['id'] ? 'selected' : '' }}>{{ $category['name'] }}</option>
                    @endforeach
                </select>
                @error('categories') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Atributos -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Atributos</label>
                <div id="attributes-container"></div>
                @error('attributes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Imagens -->
            <div class="mb-6">
                <label for="images" class="block text-sm font-medium text-gray-700">Imagens</label>
                <input type="file" name="images[]" id="images" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" multiple>
                @error('images') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

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

            <div class="mb-6">
                <button type="submit" class="w-full bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('categories').addEventListener('change', function() {
            const categoryId = this.value;
            if (categoryId) {
                // Fazer a requisição AJAX para pegar os atributos dessa categoria
                fetch(`/product/attributes/${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('attributes-container');
                        container.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(attribute => {
                                const div = document.createElement('div');
                                div.classList.add('mb-6');

                                const label = document.createElement('label');
                                label.setAttribute('for', `attribute_${attribute.attribute_id}`);
                                label.classList.add('block', 'text-sm', 'font-medium', 'text-gray-700');
                                label.textContent = attribute.name;

                                const input = document.createElement('input');
                                input.type = 'text';
                                input.name = `attributes[${attribute.attribute_id}]`;
                                input.attribute_id = `attribute_${attribute.attribute_id}`;
                                input.classList.add('mt-1', 'block', 'w-full', 'px-4', 'py-3', 'border', 'border-gray-300', 'rounded-md', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
                                input.placeholder = `Informe o valor para o atributo ${attribute.name}`;

                                div.appendChild(label);
                                div.appendChild(input);

                                container.appendChild(div);
                            });
                        }
                    });
            } else {
                document.getElementById('attributes-container').innerHTML = '';
            }
        });

        // Triggers the category change on page load to load the correct attributes
        document.getElementById('categories').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
