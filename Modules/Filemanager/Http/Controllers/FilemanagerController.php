<?php

namespace Modules\Filemanager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Filemanager\Dto\GeneratePathFileDTO;
use Modules\Filemanager\Entities\Files;
use Modules\Filemanager\Http\Repository\Interfaces\FileInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Filemanager-Filemanager
 */
class FilemanagerController extends Controller
{
    private $fileRepository;

    public $modelClass = Files::class;

    public function __construct(FileInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->get('filter');
        $filter = [];
        if (! empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }

        $query = QueryBuilder::for($this->modelClass);
        if (! empty($request->title)) {
            $query->where('title', 'ILIKE', '%'.$request->title.'%');
        }
        $query->allowedFilters($filter);
        $query->allowedAppends($request->include);
        $query->allowedSorts($request->sort);

        return $query->paginate($request->per_page);
    }

    /**
     * Filemanager Uploads
     *
     * @bodyParam files file required File
     */
    public function uploads(Request $request)
    {
        $files = $request->file('files');

        if (is_array($files)) {

            if (! in_array($files[0]->extension(), explode(',', Files::VALIDATE_EXT))) {
                return response()->json('Unknown extension')->setStatusCode(422);
            }

            $response = [];
            foreach ($files as $file) {
                $dto = new GeneratePathFileDTO();
                $dto->file = $file;
                $dto->folder_id = $request->get('folder_id');
                $response[] = $this->fileRepository->uploadFile($dto);
            }

            return $response;
        } else {
            if (! in_array($files->extension(), explode(',', Files::VALIDATE_EXT))) {
                return response()->json('Unknown extension')->setStatusCode(422);
            }
            $dto = new GeneratePathFileDTO();
            $dto->file = $files;
            $dto->folder_id = $request->get('folder_id');

            return $this->fileRepository->uploadFile($dto);
        }
    }

    public function frontUpload(Request $request)
    {
        $files = $request->file('files');

        if (is_array($request->file('files'))) {

            if (! in_array($files[0]->extension(), ['jpeg', 'jpg', 'png', 'mp4'])) {
                return response()->json('Unknown extension')->setStatusCode(422);
            }

            $response = [];
            foreach ($files as $file) {
                $dto = new GeneratePathFileDTO();
                $dto->file = $file;
                $dto->folder_id = $request->get('folder_id');
                $response[] = $this->fileRepository->uploadFile($dto, true);
            }

            return $response;
        } else {
            if (! in_array($files->extension(), ['jpeg', 'jpg', 'png', 'mp4'])) {
                return response()->json('Unknown extension')->setStatusCode(422);
            }
            $dto = new GeneratePathFileDTO();
            $dto->file = $files;
            $dto->folder_id = $request->get('folder_id');

            return $this->fileRepository->uploadFile($dto, true);
        }
    }

    public function create(Request $request)
    {
        $request->validate($this->modelClass::rules());

        return $this->modelClass::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'string']);
        /**
         * @var Files $model
         */
        $model = $this->modelClass::findOrFail(intval($id));
        $model->update(['title' => $request->title]);

        return $model;
    }

    public function delete(int $id)
    {
        $model = $this->modelClass::findOrFail($id);

        $model->delete();

        return 'deleted';
    }
}
