<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\FaqInterface;
use App\Models\Faq;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Modules\Translations\Entities\Langs;
use Spatie\QueryBuilder\QueryBuilder;

class FaqRepository extends BaseRepository implements FaqInterface
{
    use Crud;

    public $modelClass = Faq::class;

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

    public function show(Request $request, Faq $faq)
    {
        $query = QueryBuilder::for($faq);
        $this->sortFilterInclude($request, $query);

        return $query;
    }

    public function create(Request $request)
    {
        return $this->createData($request);
    }

    public function update(Request $request, Faq $faq)
    {
        return $this->updateData($request, $faq);
    }

    public function delete(Faq $faq)
    {
        return $this->deleteData($faq);
    }
}
