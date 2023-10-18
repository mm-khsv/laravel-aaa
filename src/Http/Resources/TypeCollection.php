<?php

namespace dnj\AAA\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeCollection extends ResourceCollection
{
    public $collects = TypeLocalizedResource::class;

    public function __construct($resource, bool $localized = true)
    {
        if (!$localized) {
            $this->collects = TypeResource::class;
        }
        parent::__construct($resource);
    }
}
