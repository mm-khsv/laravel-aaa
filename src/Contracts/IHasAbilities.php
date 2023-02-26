<?php

namespace dnj\AAA\Contracts;

interface IHasAbilities
{
    public function can(string $ability): bool;

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
