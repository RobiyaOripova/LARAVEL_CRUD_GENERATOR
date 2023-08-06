<?php

namespace App\Http\Repositories;

use App\Models\MenuItem;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Interfaces\MenuItemInterface;


class MenuItemRepository extends BaseRepository implements MenuItemInterface
{
    use Crud;

    public  $modelClass = MenuItem::class;

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

    public function show(Request $request, MenuItem $menuItem)
    {
        $query = QueryBuilder::for($menuItem);
        $this->sortFilterInclude($request, $query);
        return $query;
    }

    public function create(Request $request)
      {
          return $this->createData($request);
      }

      public function update(Request $request, MenuItem $menuItem)
      {
          return $this->updateData($request,$menuItem);
      }

      public function delete(MenuItem $menuItem)
      {
          return $this->deleteData($menuItem);
      }

}
