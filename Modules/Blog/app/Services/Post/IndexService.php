<?php

namespace Modules\Blog\Services\Post;

use Illuminate\Http\Request;
use Modules\Blog\Models\Post;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Transformers\PostResource;

class IndexService
{
    /**
     * Handle the index request and return structured data
     */
    public function request(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Execute the index operation
     */
    public function index(): array
    {
        $query = Post::with(['user', 'categories'])
            ->where('status', PostStatus::PUBLISHED)
            ->orderBy('published_at', 'desc');

        // Apply search filter
        if ($this->request->has('search')) {
            $search = $this->request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($this->request->has('category')) {
            $categoryName = $this->request->get('category');
            $query->whereHas('categories', function($q) use ($categoryName) {
                $q->where('categories.name', 'like', "%{$categoryName}%");
            });
        }

        $posts = $query->paginate(15);

        return [
            'status' => 'success',
            'result' => PostResource::collection($posts->items()),
            'paginate' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'last_page' => $posts->lastPage(),
            ]
        ];
    }
}
