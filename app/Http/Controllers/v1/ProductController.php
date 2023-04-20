<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Product\AddStockProductRequest;
use App\Http\Requests\v1\Product\ProductIndexRequest;
use App\Http\Requests\v1\Product\ProductShowRequest;
use App\Http\Requests\v1\Product\ProductStoreRequest;
use App\Http\Requests\v1\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Services\v1\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $products;

    /**
     * ProductController constructor.
     * @param ProductService $products
     */
    public function __construct(ProductService $products)
    {
        $this->products = $products;
    }

    /**
     * @param ProductIndexRequest $request
     * @return JsonResponse
     */
    public function index(ProductIndexRequest $request): JsonResponse
    {
        return Response::run($this->products->repository->get());
    }

    /**
     * @param ProductStoreRequest $request
     * @return JsonResponse
     */
    public function store(ProductStoreRequest $request): JsonResponse
    {
        return Response::run($this->products->repository->create($request->validated()));
    }

    /**
     * @param ProductUpdateRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        return Response::run($this->products->repository->update($product, $request->validated()));
    }

    /**
     * @param ProductShowRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function show(ProductShowRequest $request, Product $product): JsonResponse
    {
        return Response::run($this->products->repository->find($product->id));
    }

    /**
     * @param ProductShowRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function stock(ProductShowRequest $request, Product $product): JsonResponse
    {
        return Response::run($this->products->repository->stock($product));
    }

    /**
     * @param AddStockProductRequest $request
     * @return JsonResponse
     */
    public function addStock(AddStockProductRequest $request): JsonResponse
    {
        return Response::run($this->products->repository->addStock($request->validated()));
    }
}
