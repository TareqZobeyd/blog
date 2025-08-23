<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CreateCategoryRequest;
use Modules\Blog\Http\Requests\UpdateCategoryRequest;
use Modules\Blog\Models\Category;
use Modules\Blog\Services\Category\CreateService;
use Modules\Blog\Services\Category\DeleteService;
use Modules\Blog\Services\Category\IndexService;
use Modules\Blog\Services\Category\ShowService;
use Modules\Blog\Services\Category\UpdateService;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $result = app(IndexService::class)->request($request)->index();
        
        // Return appropriate status code based on result
        if ($result['status'] === 'success') {
            return response()->json($result, 200);
        }
        
        // If there's an error, return 400
        return response()->json($result, 400);
    }

    /**
     * Store a newly created category
     */
    public function store(CreateCategoryRequest $request)
    {
        $result = app(CreateService::class)->create($request);
        
        if ($result['status'] === 'success') {
            return response()->json($result, 201);
        }
        
        // Return 422 for business logic errors
        return response()->json($result, 422);
    }

    /**
     * Display the specified category
     */
    public function show(Request $request, Category $category)
    {
        $result = app(ShowService::class)->request($request)->show($category->id);
        
        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    /**
     * Update the specified category
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $result = app(UpdateService::class)->update($request, $category);
        
        if ($result['status'] === 'success') {
            return response()->json($result, 200);
        }
        
        // Return 422 for validation errors or business logic errors
        return response()->json($result, 422);
    }

    /**
     * Remove the specified category
     */
    public function destroy(Request $request, Category $category)
    {
        $result = app(DeleteService::class)->request($request)->delete($category);
        
        if ($result['status'] === 'success') {
            return response()->json(null, 204);
        }
        
        return response()->json($result, 400);
    }
}
