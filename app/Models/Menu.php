<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This is the model class for table "menu".
 *
 * @property string $title
 * @property string $alias
 * @property int $type
 * @property int $lang
 * @property string $lang_hash
 * @property int $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Menu extends Model
{
    use Translatable;
    use SoftDeletes;

    protected $table = 'menu';

    protected $fillable = ['title', 'alias', 'type', 'lang', 'lang_hash', 'status', 'deleted_at', 'created_at', 'updated_at'];

    protected $appends = [
        'translations',
        'menuItems',
    ];

    public function setTitleAttribute($value)
    {
        $this->setTranslation($value, 'title');
    }

    public function getTranslationsAttribute(): array
    {
        return $this->getTranslation($this);
    }

    public function getMenuItemsAttribute(): Collection|array
    {
        return MenuItem::query()->where(['menu_id' => $this->id, 'menu_item_parent_id' => null])->orderBy('sort', 'ASC')->get();
    }
}
