<?php

namespace App\Http\Repositories;

use App\Models\Post;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Modules\Translations\Entities\Langs;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Interfaces\PostInterface;


class PostRepository extends BaseRepository implements PostInterface
{
    use Crud;

    public  $modelClass = Post::class;

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

    public function show(Request $request, Post $post)
    {
        $query = QueryBuilder::for($post);
        $this->sortFilterInclude($request, $query);
        return $query;
    }

    public function create(Request $request)
      {
          return $this->createData($request);
      }

      public function update(Request $request, Post $post)
      {
          return $this->updateData($request,$post);
      }

      public function delete(Post $post)
      {
          return $this->deleteData($post);
      }

}
