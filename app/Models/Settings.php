<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Filemanager\Entities\Files;

/**
 * This is the model class for table "settings".
 *
 * @property string $name
 * @property string $value
 * @property int $file_id
 * @property string $slug
 * @property string $link
 * @property string $alias
 * @property string $lang_hash
 * @property int $sort
 * @property int $lang
 * @property int $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Settings extends Model
{
    use Translatable;
    use SoftDeletes;

    protected $table = 'settings';

    protected $fillable = ['name', 'value', 'file_id', 'slug', 'link', 'alias', 'lang_hash', 'sort', 'lang', 'status', 'deleted_at', 'created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->setTranslation($value, 'name');
    }

    public function getTranslationsAttribute(): array
    {
        return $this->getTranslation($this);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class);
    }
}
