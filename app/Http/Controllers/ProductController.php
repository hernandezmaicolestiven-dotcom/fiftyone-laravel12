<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
class ProductController extends Controller
{
public function index()
{
    $products = Product::all();
    return view('productos', compact('products'));
// $products = Product::query()
// ->latest()
// ->paginate(10);
// return ProductResource::collection($products);
}
public function store(StoreProductRequest $request)
{
$product = Product::create($request->validated());
return (new ProductResource($product))
->response()
->setStatusCode(201);
}
public function show(Product $product)
{
return new ProductResource($product);
}
public function update(UpdateProductRequest $request, Product $product)
{
$product->update($request->validated());
return new ProductResource($product->refresh());
}
public function destroy(Product $product)
{
$product->delete();
return response()->json(null, 204);
}
}