<?php

namespace dnj\AAA\Contracts;

interface ITypeLocalizedDetails
{
    public function getLang(): string;

    public function getTypeID(): int;

    public function getTitle(): string;
}
