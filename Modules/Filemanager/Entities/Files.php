<?php

namespace Modules\Filemanager\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Filemanager\Helpers\FilemanagerHelper;

class Files extends Model
{
    const VALIDATE_EXT = 'mp4,mpeg,avi,3gp,mov,jpeg,jpg,svg,png,doc,docx,xls,xlsx,pdf,zip,rar,mp3,ogg';

    protected $table = 'files';

    protected $fillable = [
        'title',
        'description',
        'slug',
        'ext',
        'file',
        'folder',
        'domain',
        'user_id',
        'path',
        'size',
        'folder_id',
        'is_front',
    ];

    protected $appends = [
        'thumbnails',
    ];

    /**
     * @return bool
     */
    public function getIsImage()
    {
        return FileManagerHelper::getImagesExt();
    }

    /**
     * @return string
     */
    public function getDist()
    {
        return $this->path.'/'.$this->file;
    }

    public function getUserAttribute()
    {
        return $this->hasOne('App\User');
    }

    /**
     * @return mixed
     */
    public function getThumbnailsAttribute()
    {
        $thumbsImages = FileManagerHelper::getThumbsImage();
        foreach ($thumbsImages as &$thumbsImage) {
            $slug = $thumbsImage['slug'];
            $newFileDist = config('system.STATIC_URL').$this->folder.$this->slug.'_'.$slug.'.'.$this->ext;
            $thumbsImage['src'] = $newFileDist;
            $thumbsImage['path'] = $this->path.$this->slug.'_'.$slug.'.'.$this->ext;
        }

        return $thumbsImages;
    }
}
