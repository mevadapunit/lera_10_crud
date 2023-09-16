<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ProductTransaction;
use Illuminate\View\View;
use Illuminate\Support\Str; 

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(5);
      
        return view('products.index',compact('products'))
                    ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {  
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required',
        ]);

        $product = new Product([
            'name' => $request->input('name'),
            'detail' => $request->input('detail'),
            'category' => implode(', ', $request->input('category')),
        ]);

        $product->save();

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageName      = uniqid() . '.' .$image->getClientOriginalExtension();
                $path           = public_path('uploads/product'); 
                $image->move($path, $imageName);

                ProductTransaction::create([
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {  
        $product->load('product_transactions');

        $product->category;
        $product->slug;    
        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {   
        $product->load('product_transactions');

        $categories = explode(', ', $product->category);
        $product->slug; 
        return view('products.edit', compact('product', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
            'category' => 'required', 
        ]);

        $product->name = $request->input('name');
        $product->detail = $request->input('detail');
        $product->category = implode(', ', $request->input('category'));

        $product->save();

        return redirect()->route('products.index')
                        ->with('success', 'Product updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
       
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }

    public function checkProductName(Request $request)
    {  
        if ($request->ajax()) {
            $productName = $request->input('product_name');
            $product = Product::where('name', $productName)->first();

            if ($product) {
               return response()->json(['error' => true]);
            }

            return response()->json(['message' => 'Product name is available'], 200);
        }
    }

}
