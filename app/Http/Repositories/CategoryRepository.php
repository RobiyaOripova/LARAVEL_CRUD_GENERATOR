<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\CategoryInterface;
use App\Models\Category;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    use Crud;

    public $modelClass = Category::class;

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

    public function show(Request $request, Category $category)
    {
        $query = QueryBuilder::for($category);
        $this->sortFilterInclude($request, $query);

        return $query;
    }

    public function create(Request $request)
    {
        return $this->createData($request);
    }

    public function update(Request $request, Category $category)
    {
        return $this->updateData($request, $category);
    }

    public function delete(Category $category)
    {
        return $this->deleteData($category);
    }
}
