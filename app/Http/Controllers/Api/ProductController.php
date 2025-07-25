<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// import model product
use App\Models\Product;
// import productresource
use App\Http\Resources\ProductResource;
// validator
use Illuminate\Support\Facades\Validator;
//storage
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // get semua data product
        // $products=Product::latest()->paginate(10);
        $products=Product::get();
        return new ProductResource(true,'data product',$products);
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
                    'image' => 'required|image|mimes:jpg,png|max:2048',
                    'title' => 'required',
                    'description' => 'required',
                    'price' => 'required|numeric',
                    'stock' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        $image =$request->file('image');
        $image->storeAs('products',$image->hashName());

        // insert product
        $product=Product::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return new ProductResource(true,'add product',$product);
    }

    public function show($id)
    {
        $product = Product::find($id);
        return new ProductResource(true,'show product',$product);
    }

    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
                    'image'     => 'image|mimes:jpg,png|max:2048',
                    'title'     => 'required',
                    'description' => 'required',
                    'price'     => 'required|numeric',
                    'stock'     => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }
        // cari product yg mau di update
        $product = Product::find($id);

        // update gambar apa nggak ( cek gambar not empty)
        if ($request->hasFile('image')) {
            // hapus existing image
            Storage::delete('products/'.basename($product->image));

            // upload image nya
            $image =$request->file('image');
            $image->storeAs('products',$image->hashName());

            $product->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);
        }else{
            $product->update([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);
        }

        return new ProductResource(true,'update product',$product);
    }

    public function destroy($id)
    {
            // cari product yg mau di delet
        $product = Product::find($id);
        // hapus existing image
        Storage::delete('products/'.basename($product->image));
        // hapus data product
        $product->delete();

        // response
        return new ProductResource(true,'delete product',null);
    }
}
