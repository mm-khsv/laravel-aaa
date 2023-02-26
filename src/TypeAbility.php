<?php

namespace dnj\AAA;

use Illuminate\Database\Eloquent\Model;

class TypeAbility extends Model
{
    /**
     * @var string
     */
    protected $table = 'aaa_types_abilities';

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
