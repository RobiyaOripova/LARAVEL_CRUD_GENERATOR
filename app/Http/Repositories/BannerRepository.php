<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\BannerInterface;
use App\Models\Banner;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BannerRepository extends BaseRepository implements BannerInterface
{
    use Crud;

    public $modelClass = Banner::class;

    public function index(Request $request)
    {
        $query = QueryBuilder::for($this->modelClass);
        $this->sortFilterInclude($request, $query);

        return $query;
    }

    public function adminIndex(Request $request)
    {
        $query = QueryBuilder::for($this->modelClass);
        $this->sortFilterInclude($request, $query);

        return $query;
    }

    public function show(Request $request, Banner $banner)
    {
        $query = QueryBuilder::for($banner);
        $this->sortFilterInclude($request, $query);

        return $query;
    }

    public function create(Request $request)
    {
        return $this->createData($request);
    }

    public function update(Request $request, Banner $banner)
    {
        return $this->updateData($request, $banner);
    }

    public function delete(Banner $banner)
    {
        return $this->deleteData($banner);
    }
}
