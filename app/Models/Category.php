<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'color',
        'description',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected static function booted()
    {
        static::saving(function ($category) {
            // Set category to default
            if ($category->is_default) {
                $oldDefault = Category::where('is_default', true)
                    ->where('category_id', '!=', $category->category_id)
                    ->first();

                if ($oldDefault) {
                    $oldDefault->is_default = false;
                    $oldDefault->save();
                }
            } else {
                if (!Category::where('is_default', true)->exists()) {
                    $category->is_default = true;
                }
            }
        });
    }
}
