<?php

namespace dnj\AAA\Contracts;

interface IHasPassword
{
    public function verifyPassword(string $password): bool;
}
