<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CreatePostRequest;
use Modules\Blog\Http\Requests\UpdatePostRequest;
use Modules\Blog\Models\Post;
use Modules\Blog\Services\Post\CreateService;
use Modules\Blog\Services\Post\DeleteService;
use Modules\Blog\Services\Post\IndexService;
use Modules\Blog\Services\Post\ShowService;
use Modules\Blog\Services\Post\UpdateService;

class PostController extends Controller
{
    /**
     * Display a listing of posts
     */
    public function index(Request $request)
    {
        $result = app(IndexService::class)->request($request)->index();
        
        return response()->json($result);
    }

    /**
     * Store a newly created post
     */
    public function store(CreatePostRequest $request)
    {
        $result = app(CreateService::class)->create($request);
        
        return response()->json($result, $result['status'] === 'success' ? 201 : 400);
    }

    /**
     * Display the specified post
     */
    public function show(Request $request, Post $post)
    {
        $result = app(ShowService::class)->show($post->id);
        
        return response()->json($result, $result['status'] === 'success' ? 200 : 404);
    }

    /**
     * Update the specified post
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $result = app(UpdateService::class)->update($request, $post);
        
        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }

    /**
     * Remove the specified post
     */
    public function destroy(Request $request, Post $post)
    {
        $result = app(DeleteService::class)->delete($post);
        
        return response()->json($result, $result['status'] === 'success' ? 200 : 400);
    }
}

