<?php

namespace Modules\Filemanager\Dto;

use Illuminate\Http\UploadedFile;

class GeneratePathFileDTO
{
    /**
     * @var UploadedFile
     */
    public $file;

    public $folder_id;

    public $useFileName = false;
}
