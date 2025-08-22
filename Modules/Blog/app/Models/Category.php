<?php

namespace Modules\Blog\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Blog\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Get the posts that belong to this category.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post')
                    ->withTimestamps();
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
