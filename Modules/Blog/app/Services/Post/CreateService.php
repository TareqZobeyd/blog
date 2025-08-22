<?php

namespace Modules\Blog\Services\Post;

use Modules\Blog\Http\Requests\CreatePostRequest;
use Modules\Blog\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateService
{
    /**
     * Execute the create operation
     */
    public function create(CreatePostRequest $request): array
    {
        $validatedData = $request->validated();
        
        // Extract category_ids for relationship
        $categoryIds = $validatedData['category_ids'];
        unset($validatedData['category_ids']);
        
        // Handle image upload
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('posts', 'public');
            $validatedData['img'] = $imagePath;
        }
        
        // Add user_id and ensure status is draft
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'draft'; // Always draft for new posts
        
        // Create post
        $post = Post::create($validatedData);
        
        // Attach categories
        $post->categories()->attach($categoryIds);

        return [
            'status' => 'success',
            'result' => $post->load('categories'),
            'message' => 'پست با موفقیت ایجاد شد (وضعیت: پیش‌نویس)'
        ];
    }
}
