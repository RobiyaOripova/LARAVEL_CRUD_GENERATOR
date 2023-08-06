<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * This is the model class for table "categoryables".
 *
 * @property int $category_id
 * @property int $categoryable_id
 * @property string $categoryable_type
 * @property string $created_at
 * @property string $updated_at
 */
class Categoryable extends Model
{
    protected $table = 'categoryables';

    protected $fillable = ['category_id', 'categoryable_id', 'categoryable_type', 'created_at', 'updated_at'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryable(): MorphTo
    {
        return $this->morphTo();
    }
}
