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
        \Log::info('UpdateService: Starting update process', [
            'post_id' => $post->id,
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role ?? 'unknown'
        ]);
        
        // Check authorization
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $post->user_id !== $user->id) {
            \Log::warning('UpdateService: Authorization failed', [
                'user_id' => $user->id,
                'post_user_id' => $post->user_id,
                'is_super_admin' => $user->isSuperAdmin()
            ]);
            return [
                'status' => 'error',
                'message' => 'شما فقط می‌توانید پست‌های خودتان را ویرایش کنید',
                'result' => null
            ];
        }

        // Force Form Data handling
        $validatedData = $request->all(); // تغییر از validated() به all()
        
        \Log::info('UpdateService: Data received', [
            'validated_data' => $validatedData,
            'has_status' => isset($validatedData['status']),
            'status_value' => $validatedData['status'] ?? 'not_set'
        ]);
        
        // اگر user عادی است و status ارسال کرده، خطا بده
        if (!$user->isSuperAdmin() && isset($validatedData['status'])) {
            \Log::warning('UpdateService: Regular user trying to update status', [
                'user_id' => $user->id,
                'status_requested' => $validatedData['status']
            ]);
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
        \Log::info('UpdateService: Attempting to update post', [
            'post_id' => $post->id,
            'data_to_update' => $validatedData,
            'post_before' => $post->toArray()
        ]);
        
        $success = $post->update($validatedData);
        
        \Log::info('UpdateService: Update result', [
            'success' => $success,
            'post_after' => $post->fresh()->toArray()
        ]);
        
        if (!$success) {
            \Log::error('UpdateService: Update failed', [
                'post_id' => $post->id,
                'data' => $validatedData
            ]);
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
        
        \Log::info('UpdateService: Update completed successfully', [
            'post_id' => $post->id,
            'final_status' => $post->status,
            'final_data' => $post->toArray()
        ]);

        return [
            'status' => 'success',
            'result' => $post,
            'message' => 'پست با موفقیت بروزرسانی شد'
        ];
    }
}
