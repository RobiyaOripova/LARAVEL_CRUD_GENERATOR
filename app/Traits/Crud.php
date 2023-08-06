<?php

namespace App\Traits;

use Spatie\QueryBuilder\AllowedFilter;

trait Crud
{
    public function createData($request)
    {
        $model = $this->modelClass::create($request->validated());
        $model->load(! empty($request->include) ? explode(',', $request->get('include')) : []);

        return $model;
    }

    public function updateData($request, $model)
    {
        $model->update($request->validated());
        $model->load(! empty($request->include) ? explode(',', $request->get('include')) : []);

        return $model;
    }

    public function deleteData($model)
    {
        $model->delete();

        return $model;
    }

    public function sortFilterInclude($request, $query)
    {
        $filters = $request->get('filter');
        $filter = [];
        if (! empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query->allowedFilters($filter);
        $query->allowedIncludes(! empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedSorts($request->get('sort'));

        return $query;
    }
}
