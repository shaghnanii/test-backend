<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryIndexRequest;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use ApiResponseTrait;


    public function index(CategoryIndexRequest $request)
    {
        try {
            $categories = Category::query()->get();
            return $this->sendResponse($categories, 'index', Category::class);
        }
        catch (\Exception $exception)
        {
            return $this->response500($exception->getMessage());
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $category = Category::query()->create($request->only((new Category)->getFillable()));

            return $this->sendResponse($category, 'store', Category::class);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->response500($exception->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = Category::query()->find($id);
            return $this->sendResponse($category, 'show',Category::class);
        }
        catch (\Exception $exception)
        {
            return $this->response500($exception->getMessage());
        }
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $category = Category::query()->find($id);
            if (!$category)
                return $this->response404("No category found with the provided id.");
            $category->update($request->only((new Category)->getFillable()));

            DB::commit();
            return $this->sendResponse($category, 'update',Category::class);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->response500($exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $category = Category::query()->find($id);
            if (!$category)
                return $this->response404("No category found with the provided id.");

            $category->delete();

            DB::commit();
            return $this->sendResponse($category, 'destroy',Category::class);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->response500($exception->getMessage());
        }
    }
}
