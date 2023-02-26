<?php

namespace dnj\AAA;

use dnj\AAA\Contracts\ITypeLocalizedDetails;
use Illuminate\Database\Eloquent\Model;

class TypeLocalizedDetails extends Model implements ITypeLocalizedDetails
{
    /**
     * @var string
     */
    protected $table = 'aaa_types_translates';

    public function getID(): int
    {
        return $this->id;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getTypeID(): int
    {
        return $this->type_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
