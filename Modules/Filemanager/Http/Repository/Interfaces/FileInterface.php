<?php

namespace Modules\Filemanager\Http\Repository\Interfaces;

use Modules\Filemanager\Dto\GeneratePathFileDTO;
use Modules\Filemanager\Entities\Files;

interface FileInterface
{
    /**
     * @return mixed
     */
    public function generatePath(GeneratePathFileDTO $generatePathFileDTO);

    /**
     * @return mixed
     */
    public function uploadFile(GeneratePathFileDTO $dto);

    /**
     * @return mixed
     */
    public function downloadFile(Files $file, $type);
}
