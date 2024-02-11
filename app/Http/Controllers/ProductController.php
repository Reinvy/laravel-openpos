<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //index
    function index(Request $request)
    {
        $products =  Product::when($request->input('name'), function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        })->paginate(10);
        return view('pages.products.index', compact('products'));
    }

    //show
    function show()
    {
        return view('pages.products.show');
    }

    //create
    function create()
    {
        $categories = Category::get();
        return view('pages.products.create', compact('categories'));
    }

    //edit
    function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::get();
        return view('pages.products.edit', compact('product', 'categories'));
    }


    //store
    function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'status' => 'required|boolean',
            'is_favorite' => 'required|boolean',
        ]);

        $product = new Product();
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->is_favorite = $request->is_favorite;
        $product->save();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products/', $product->id . '.' . $image->getClientOriginalExtension());
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    //update
    function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'status' => 'required|boolean',
            'is_favorite' => 'required|boolean',
        ]);

        $product = Product::find($id);
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->is_favorite = $request->is_favorite;
        $product->save();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products/', $product->id . '.' . $image->getClientOriginalExtension());
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'User updated successfully');
    }

    //destroy
    function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
