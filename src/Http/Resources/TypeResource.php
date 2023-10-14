<?php

namespace dnj\AAA\Http\Resources;

use dnj\AAA\Http\Resources\Concerns\HasMeta;
use dnj\Localization\Http\Resources\HasTranslate;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Type $resource
 */
class TypeResource extends JsonResource
{
    use HasMeta;
    use HasTranslate;

    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            $this->localize(),
            $this->exportMeta(),
            ['children' => $this->resource->getChildIds()],
            ['abilities' => $this->resource->getAbilities()],
        );
    }
}
