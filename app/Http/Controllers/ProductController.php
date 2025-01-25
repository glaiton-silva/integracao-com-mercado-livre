<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Http\Controllers\MercadoLivreController;

class ProductController extends Controller
{
    protected $mercadoLivreController;

    public function __construct(MercadoLivreController $mercadoLivreController)
    {
        $this->mercadoLivreController = $mercadoLivreController;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {    
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048',
            'categories' => 'required|string',
            'attributes' => 'nullable|array',
        ]);
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $images[] = $path;
            }
        }

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'images' => json_encode($images),
            'categories' => $validated['categories'],
            'attributes' => json_encode($validated['attributes'] ?? []),
        ]);
        
        $mercadoLivreProductData = [
            'title' => $validated['name'],
            'category_id' => $validated['categories'],
            'price' => $validated['price'],
            'currency_id' => 'BRL',
            'available_quantity' => $validated['quantity'],
            'pictures' => array_map(fn($image) => ['source' => asset('storage/' . $image)], $images),
            'attributes' => $validated['attributes'],
        ];

        $this->mercadoLivreController->createOrUpdateProduct($mercadoLivreProductData);

        return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $attributes = $product->category ? $product->category->attributes : [];

        return view('products.edit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048',
            'categories' => 'required|string',
            'attributes' => 'nullable|array',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $images[] = $path;
            }
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'images' => json_encode($images),
            'categories' => $validated['categories'],
            'attributes' => json_encode($validated['attributes'] ?? []),
        ]); 
        
        $mercadoLivreProductData = [
            'title' => $validated['name'],
            'category_id' => $validated['categories'],
            'price' => $validated['price'],
            'currency_id' => 'BRL',
            'available_quantity' => $validated['quantity'],
            'pictures' => array_map(fn($image) => ['source' => asset('storage/' . $image)], $images),
            'attributes' => $validated['attributes'],
        ];

        $this->mercadoLivreController->createOrUpdateProduct($mercadoLivreProductData, $product->id);

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->mercadoLivreController->createOrUpdateProduct([], $product->id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso!');
    }

    public function getAttributes($categoryId)
    {
        $attributes = Attribute::where('category_id', $categoryId)->get();
        return response()->json($attributes);
    }

}
