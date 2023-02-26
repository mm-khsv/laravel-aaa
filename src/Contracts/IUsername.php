<?php

namespace dnj\AAA\Contracts;

interface IUsername
{
    public function getId(): int;

    public function getUserID(): int;

    public function getUsername(): string;
}
