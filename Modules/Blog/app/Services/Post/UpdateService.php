<?php

namespace Modules\Blog\Services\Post;

use Modules\Blog\Http\Requests\UpdatePostRequest;
use Modules\Blog\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class UpdateService
{
    /**
     * Execute the update operation
     */
    public function update(UpdatePostRequest $request, Post $post): array
    {
        // Check authorization
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $post->user_id !== $user->id) {
            return [
                'status' => 'error',
                'message' => 'شما فقط می‌توانید پست‌های خودتان را ویرایش کنید',
                'result' => null
            ];
        }

        // Force Form Data handling
        $validatedData = $request->all();
        
        // Auto-set published_at when status changes to published
        if (isset($validatedData['status']) && $validatedData['status'] === 'published') {
            if (!isset($validatedData['published_at']) || empty($validatedData['published_at'])) {
                $validatedData['published_at'] = now();
            }
        }
        
        // Auto-set published_at to null when status changes to draft
        if (isset($validatedData['status']) && $validatedData['status'] === 'draft') {
            $validatedData['published_at'] = null;
        }
        
        // Regular users cannot update status
        if (!$user->isSuperAdmin() && isset($validatedData['status'])) {
            return [
                'status' => 'error',
                'message' => 'شما نمی‌توانید وضعیت پست را تغییر دهید',
                'result' => null
            ];
        }
        
        // Extract category_ids for relationship if provided
        $categoryIds = null;
        if (isset($validatedData['category_ids'])) {
            $categoryIds = $validatedData['category_ids'];
            unset($validatedData['category_ids']);
        }
        
        // Update post
        $success = $post->update($validatedData);
        
        if (!$success) {
            return [
                'status' => 'error',
                'message' => 'خطا در بروزرسانی پست',
                'result' => null
            ];
        }
        
        // Update categories if provided
        if ($categoryIds !== null) {
            $post->categories()->sync($categoryIds);
        }
        
        // Reload post with relationships
        $post->load(['user', 'categories']);

        return [
            'status' => 'success',
            'result' => $post,
            'message' => 'پست با موفقیت بروزرسانی شد'
        ];
    }
}
