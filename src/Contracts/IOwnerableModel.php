<?php

namespace dnj\AAA\Contracts;

interface IOwnerableModel
{
    public function getOwnerUserId(): ?int;

    public function getOwnerUserColumn(): string;

    public function hasUserAccess(IUser $user): bool;
}
