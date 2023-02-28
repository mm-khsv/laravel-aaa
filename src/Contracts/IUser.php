<?php

namespace dnj\AAA\Contracts;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;

interface IUser extends IHasAbilities, Authenticatable, Authorizable
{
    public function getId(): int;

    public function getName(): string;

    public function getTypeId(): int;

    public function getStatus(): UserStatus;

    /**
     * @return array<mixed,mixed>
     */
    public function getMeta(): array;
}
