<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Filemanager\Entities\Files;

/**
 * This is the model class for table "faq".
 *
 * @property string $question
 * @property string $answer
 * @property int $sort
 * @property int $file_id
 * @property int $lang
 * @property string $lang_hash
 * @property int $status
 * @property int $type
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Faq extends Model
{
    use Translatable;
    use SoftDeletes;

    protected $table = 'faq';

    protected $fillable = ['question', 'answer', 'sort', 'file_id', 'lang', 'lang_hash', 'status', 'type', 'deleted_at', 'created_at', 'updated_at'];

    public function setAnswerAttribute($value)
    {
        $this->setTranslation($value, 'answer');
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
