<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Filemanager\Entities\Files;

/**
 * This is the model class for table "banners".
 *
 * @property string $title_uz
 * @property string $title_ru
 * @property string $title_en
 * @property string $description_uz
 * @property string $description_ru
 * @property string $description_en
 * @property string $url
 * @property int $viewed
 * @property int $file_id
 * @property int $sort
 * @property int $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'banners';

    protected $fillable = ['title_uz', 'title_ru', 'title_en', 'description_uz', 'description_ru', 'description_en', 'url', 'viewed', 'file_id', 'sort', 'status', 'deleted_at', 'created_at', 'updated_at'];

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class);
    }
}
