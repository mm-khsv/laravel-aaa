<?php

namespace dnj\AAA\Http\Resources;

use dnj\AAA\Contracts\IUser;
use dnj\AAA\Http\Resources\Concerns\HasMeta;
use dnj\AAA\Http\Resources\Concerns\HasSummary;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property IUser $resource
 */
class UserResource extends JsonResource
{
    use HasSummary;
    use HasMeta;

    public static array $summaryMetaKeys = [];

    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['meta'] = $this->exportMeta();
        if (!$this->summary) {
            $data['type'] = TypeLocalizedResource::make($this->resource->type);
            $data['usernames'] = UsernameResource::collection($this->resource->usernames);
        }

        return $data;
    }
}
