<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Filemanager\Entities\Files;

/**
 * This is the model class for table "menu_items".
 *
 * @property int $menu_id
 * @property string $title
 * @property string $url
 * @property int $file_id
 * @property int $sort
 * @property int $menu_item_parent_id
 * @property int $status
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class MenuItem extends Model
{
    use SoftDeletes;

    protected $table = 'menu_items';

    protected $fillable = ['menu_id', 'title', 'url', 'file_id', 'sort', 'menu_item_parent_id', 'status', 'deleted_at', 'created_at', 'updated_at'];

    protected $appends = [
        'menuItems',
        'files',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(Files::class);
    }

    public function getFilesAttribute()
    {
        if ($this->icon == null) {
            return null;
        }
        $data = Cache::tags(['file_by_id_'.$this->icon])->get('file_by_id_'.$this->icon);
        if ($data == null) {
            $data = Files::findOrFail($this->icon);
            Cache::tags(['file_by_id_'.$this->icon])->put('file_by_id_'.$this->icon, $data, 1200);
        }

        return $data;
    }

    public function getMenuItemsAttribute()
    {
        $data = Cache::tags(['menu'])->get('menu_items_parent_id_by_id_'.$this->id);
        if ($data == null) {
            $data = MenuItems::where(['menu_item_parent_id' => $this->id])->orderBy('sort', 'ASC')->get();
            Cache::tags(['menu'])->put('menu_items_parent_id_by_id_'.$this->id, $data, 3600 * 24 * 30);
        }

        return $data;
    }
}
