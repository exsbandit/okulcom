<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Category\CategoryIndexRequest;
use App\Http\Requests\v1\Category\CategoryShowRequest;
use App\Http\Requests\v1\Category\CategoryStoreRequest;
use App\Http\Requests\v1\Category\CategoryUpdateRequest;
use App\Http\Resources\v1\Category\CategoryIndexResource;
use App\Models\Category;
use App\Services\v1\Category\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categories;

    /**
     * CategoryController constructor.
     * @param CategoryService $categories
     */
    public function __construct(CategoryService $categories)
    {
        $this->categories = $categories;
    }


    /**
     * @param CategoryIndexRequest $request
     * @return JsonResponse
     */
    public function index(CategoryIndexRequest $request): JsonResponse
    {
        return Response::run(CategoryIndexResource::collection($this->categories->repository->get()));
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        return Response::run($this->categories->repository->create($request->validated()));
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        return Response::run($this->categories->repository->update($category, $request->validated()));
    }

    /**
     * return JsonResponse
     */
    public function show(CategoryShowRequest $request, Category $category): JsonResponse
    {
        return Response::run($this->categories->repository->find($category->id));
    }
}
