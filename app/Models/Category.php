<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This is the model class for table "categories".
 *
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_en
 * @property int $status
 * @property int $type
 * @property int $is_special
 * @property int $sort
 * @property int $is_deleted
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = ['name_uz', 'name_ru', 'name_en', 'status', 'type', 'is_special', 'sort', 'is_deleted', 'deleted_at', 'created_at', 'updated_at'];
}
