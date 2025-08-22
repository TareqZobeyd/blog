<?php

namespace Modules\Blog\Services\Category;

use Illuminate\Http\Request;
use Modules\Blog\Models\Category;

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
        $perPage = $this->request->get('per_page', 15);
        $search = $this->request->get('search');
        $orderBy = $this->request->get('order_by', 'created_at');
        $orderDirection = $this->request->get('order_direction', 'desc');

        $query = Category::query()->withCount('posts');

        // Apply search filter
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Apply ordering
        $query->orderBy($orderBy, $orderDirection);

        $categories = $query->paginate($perPage);

        return [
            'status' => 'success',
            'result' => $categories->items(),
            'paginate' => [
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ]
        ];
    }
}
