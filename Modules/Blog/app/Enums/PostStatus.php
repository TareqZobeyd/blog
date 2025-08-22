<?php

namespace Modules\Blog\Enums;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'پیش‌نویس',
            self::PUBLISHED => 'منتشر شده',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'warning',
            self::PUBLISHED => 'success',
        };
    }
}

