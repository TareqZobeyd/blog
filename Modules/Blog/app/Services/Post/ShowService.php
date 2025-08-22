<?php

namespace Modules\Blog\Services\Post;

use Modules\Blog\Models\Post;
use Modules\Blog\Enums\PostStatus;
use Modules\Blog\Transformers\PostResource;

class ShowService
{
    /**
     * Show post by ID (only published)
     */
    public function show(int $id): array
    {
        $post = Post::with(['user', 'categories'])
            ->where('status', PostStatus::PUBLISHED)
            ->find($id);

        if (!$post) {
            return [
                'status' => 'error',
                'message' => 'پست یافت نشد',
                'result' => null
            ];
        }

        return [
            'status' => 'success',
            'result' => new PostResource($post)
        ];
    }


}
