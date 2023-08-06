<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\CountryInterface;
use App\Models\Country;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CountryRepository extends BaseRepository implements CountryInterface
{
    use Crud;

    public $modelClass = Country::class;

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

    public function show(Request $request, Country $country)
    {
        $query = QueryBuilder::for($country);
        $this->sortFilterInclude($request, $query);

        return $query;
    }

    public function create(Request $request)
    {
        return $this->createData($request);
    }

    public function update(Request $request, Country $country)
    {
        return $this->updateData($request, $country);
    }

    public function delete(Country $country)
    {
        return $this->deleteData($country);
    }
}
