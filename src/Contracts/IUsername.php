<?php

namespace dnj\AAA\Contracts;

interface IUsername
{
    public function getID(): int;

    public function getUserID(): int;

    public function getUser(): IUser;

    public function getUsername(): string;

    public function verifyPassword(string $password): bool;
}
