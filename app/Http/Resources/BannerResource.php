<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    protected function getRelations(Request $request, array $data): array
    {
        $appends = explode(',', $request->get('append'));
        if ($request->has('append') && ! empty($appends)) {
            foreach ($appends as $append) {
                if (! is_null($this->$append)) {
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
