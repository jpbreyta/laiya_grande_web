<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Compatibility model for the removed food_categories table.
 */
class FoodCategory extends CatalogCategory
{
    protected $table = 'catalog_categories';

    public function foods(): HasMany
    {
        return $this->hasMany(Foods::class, 'catalog_category_id');
    }
}
