<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Filemanager\Entities\Files;

/**
 * This is the model class for table "page".
 *
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $type
 * @property int $file_id
 * @property int $sort
 * @property string $documents
 * @property string $anons
 * @property string $content
 * @property int $lang
 * @property string $lang_hash
 * @property int $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Page extends Model
{
    use Translatable;
    use SoftDeletes;

    protected $table = 'page';

    protected $fillable = ['title', 'slug', 'description', 'type', 'file_id', 'sort', 'documents', 'anons', 'content', 'lang', 'lang_hash', 'status', 'deleted_at', 'created_at', 'updated_at'];

    public function setTitleAttribute($value)
    {
        $this->setTranslation($value, 'title');
    }

    public function getTranslationsAttribute(): array
    {
        return $this->getTranslation($this);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title.'-'.rand(1, 100));
            }
        });
    }
}
