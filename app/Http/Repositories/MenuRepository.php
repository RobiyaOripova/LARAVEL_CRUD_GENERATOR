<?php

namespace App\Http\Repositories;

use App\Models\Menu;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Modules\Translations\Entities\Langs;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Interfaces\MenuInterface;


class MenuRepository extends BaseRepository implements MenuInterface
{
    use Crud;

    public  $modelClass = Menu::class;

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

    public function show(Request $request, Menu $menu)
    {
        $query = QueryBuilder::for($menu);
        $this->sortFilterInclude($request, $query);
        return $query;
    }

    public function create(Request $request)
      {
          return $this->createData($request);
      }

      public function update(Request $request, Menu $menu)
      {
          return $this->updateData($request,$menu);
      }

      public function delete(Menu $menu)
      {
          return $this->deleteData($menu);
      }

}
