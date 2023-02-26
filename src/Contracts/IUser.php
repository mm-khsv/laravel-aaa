<?php

namespace dnj\AAA\Contracts;

interface IUser extends IHasAbilities
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
