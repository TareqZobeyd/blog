<?php

namespace Modules\Blog\Services\Post;

use Modules\Blog\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class DeleteService
{
    /**
     * Execute the delete operation
     */
    public function delete(Post $post): array
    {
        // Check authorization
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $post->user_id !== $user->id) {
            return [
                'status' => 'error',
                'message' => 'شما فقط می‌توانید پست‌های خودتان را حذف کنید',
                'result' => null
            ];
        }

        // Delete post (categories will be automatically detached due to cascade)
        $success = $post->delete();
        
        if (!$success) {
            return [
                'status' => 'error',
                'message' => 'خطا در حذف پست',
                'result' => null
            ];
        }

        return [
            'status' => 'success',
            'message' => 'پست با موفقیت حذف شد',
            'result' => null
        ];
    }
}
