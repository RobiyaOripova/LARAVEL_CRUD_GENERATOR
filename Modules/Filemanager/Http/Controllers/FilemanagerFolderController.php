<?php

namespace Modules\Filemanager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Filemanager\Entities\FolderFiles;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group FilemanagerFolder-FilemanagerFolder
 */
class FilemanagerFolderController extends Controller
{
    private $fileRepository;

    public $modelClass = FolderFiles::class;

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
            $query->where('title', 'LIKE', '%'.$request->title.'%');
        }
        $query->allowedFilters($filter);
        $query->allowedAppends($request->include);
        $query->allowedSorts($request->sort);

        return $query->paginate($request->per_page);
    }

    public function create(Request $request)
    {
        $request->validate($this->modelClass::rules());

        return $this->modelClass::create($request->all());
    }

    /**
     * @param  FolderFiles  $id
     * @return mixed|FolderFiles
     */
    public function update(Request $request, $id)
    {
        $request->validate(['title' => 'string']);
        /**
         * @var $model FolderFiles
         */
        $model = $this->modelClass::findOrFail($id);
        $model->update(['title' => $request->title]);

        return $model;
    }

    public function delete($id)
    {
        $model = $this->modelClass::findOrFail(intval($id));
        $model->delete();

        return 'deleted';
    }
}
