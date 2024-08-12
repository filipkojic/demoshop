<?php

namespace Application\Persistence\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Product
 *
 * This class represents the Product entity in the database.
 * It defines the relationship with the Category entity.
 */
class Product extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'products';

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id', 'sku', 'title', 'brand', 'price', 'short_description', 'description', 'image', 'enabled', 'featured', 'view_count',
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * Get the category that owns the product.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
