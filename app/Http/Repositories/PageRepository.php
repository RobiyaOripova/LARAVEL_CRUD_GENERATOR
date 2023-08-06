<?php

namespace App\Http\Repositories;

use App\Models\Page;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Modules\Translations\Entities\Langs;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Interfaces\PageInterface;


class PageRepository extends BaseRepository implements PageInterface
{
    use Crud;

    public  $modelClass = Page::class;

    public function index(Request $request)
    {
        $query = QueryBuilder::for($this->modelClass);
        $this->sortFilterInclude($request, $query);
        $query->where(['lang' => Langs::getLangId(Lang::getLocale())]);
        return $query;
    }

    public function adminIndex(Request $request)
    {
        $query = QueryBuilder::for($this->modelClass);
        $this->sortFilterInclude($request, $query);
        $query->where(['lang' => Langs::getLangId(Lang::getLocale())]);
        return $query;
    }

    public function show(Request $request, Page $page)
    {
        $query = QueryBuilder::for($page);
        $this->sortFilterInclude($request, $query);
        return $query;
    }

    public function create(Request $request)
      {
          return $this->createData($request);
      }

      public function update(Request $request, Page $page)
      {
          return $this->updateData($request,$page);
      }

      public function delete(Page $page)
      {
          return $this->deleteData($page);
      }

}
