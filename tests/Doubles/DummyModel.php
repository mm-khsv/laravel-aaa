<?php

namespace dnj\AAA\Tests\Doubles;

use dnj\AAA\Contracts\IOwnerableModel;
use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model implements IOwnerableModel
{
    protected $guarded = [];
    protected $table = 'aaa_dummy';

    public function getOwnerUserId(): ?int
    {
        return $this->user_id;
    }

    public function getOwnerUserColumn(): string
    {
        return 'user_id';
    }
}
