<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * This is the model class for table "country".
 *
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_en
 * @property string $code
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Country extends Model
{
    protected $table = 'country';

    protected $fillable = ['name_uz', 'name_ru', 'name_en', 'code', 'status', 'created_at', 'updated_at'];
}
