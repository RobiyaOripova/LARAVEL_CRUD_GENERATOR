<?php

namespace App\Http\Repositories;

use App\Models\Settings;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Modules\Translations\Entities\Langs;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Interfaces\SettingsInterface;


class SettingsRepository extends BaseRepository implements SettingsInterface
{
    use Crud;

    public  $modelClass = Settings::class;

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

    public function show(Request $request, Settings $settings)
    {
        $query = QueryBuilder::for($settings);
        $this->sortFilterInclude($request, $query);
        return $query;
    }

    public function create(Request $request)
      {
          return $this->createData($request);
      }

      public function update(Request $request, Settings $settings)
      {
          return $this->updateData($request,$settings);
      }

      public function delete(Settings $settings)
      {
          return $this->deleteData($settings);
      }

}
