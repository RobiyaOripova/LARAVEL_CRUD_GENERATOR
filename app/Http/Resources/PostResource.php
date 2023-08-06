<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @param array $data
     * @return array
     */
    protected function getRelations(Request $request, array $data): array
    {
        $appends = explode(',', $request->get('append'));
        if ($request->has("append") && !empty($appends)) {
            foreach ($appends as $append) {
                if (!is_null($this->$append)) {
                    $data["{$append}"] = $this->$append;
                }
            }
        }
        return $data;
    }

    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        return $this->getRelations($request, $data);
    }
}
