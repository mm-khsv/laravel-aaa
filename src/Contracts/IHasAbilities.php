<?php

namespace dnj\AAA\Contracts;

interface IHasAbilities
{
    /**
     * @param string[] $abilities
     */
    public function canAll(array $abilities): bool;

    /**
     * @param string[] $abilities
     */
    public function canAny(array $abilities): bool;

    public function cant(string $ability): bool;

    public function authorize(string $ability): void;
}
