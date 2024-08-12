<?php

namespace Application\Persistence\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * This class represents the Category entity in the database.
 * It defines relationships with subcategories and products.
 */
class Category extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'categories';

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id', 'code', 'title', 'description',
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * Get the subcategories for the category.
     *
     * @return HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products for the category.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
