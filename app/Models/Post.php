<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Filemanager\Entities\Files;

/**
 * This is the model class for table "post".
 *
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $slug
 * @property int $popular
 * @property int $type
 * @property int $file_id
 * @property string $document_ids
 * @property string $category_ids
 * @property int $video_id
 * @property int $top
 * @property int $views
 * @property string $published_at
 * @property int $lang
 * @property string $lang_hash
 * @property int $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Post extends Model
{
    use Translatable;
    use SoftDeletes;

    protected $table = 'post';

    protected $fillable = ['title', 'description', 'content', 'slug', 'popular', 'type', 'file_id', 'document_ids', 'category_ids', 'video_id', 'top', 'views', 'published_at', 'lang', 'lang_hash', 'status', 'deleted_at', 'created_at', 'updated_at'];

    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];

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

    public function video(): BelongsTo
    {
        return $this->belongsTo(Files::class);
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function getDocumentsAttribute(): Collection|array|null
    {
        if (! empty($this->document_ids)) {
            return Files::query()->whereIn('id', explode(',', $this->document_ids))->get();
        }

        return null;
    }

    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = date('Y-m-d H:i:s', $value);
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
