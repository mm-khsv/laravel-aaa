<?php

namespace dnj\AAA\Contracts;

interface IUser extends IHasAbilities
{
    public function getID(): int;

    public function getName(): string;

    public function getTypeId(): int;

    public function getType(): IType;

    public function getStatus(): UserStatus;
}
