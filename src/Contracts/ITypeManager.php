<?php

namespace dnj\AAA\Contracts;

interface ITypeManager
{
    public function find(int $id): ?IType;

    public function findOrFail(int $id): IType;

    /**
     * @param array{id?:int[],title?:string} $filters
     *
     * @return iterable<IType>
     */
    public function search(array $filters = []): iterable;

    public function getGuestTypeId(): ?int;

    public function getGuestType(): ?IType;

    /**
     * @param array<string,array{title:string}> $localizedDetails
     * @param string[]                          $abilities
     * @param int[]                             $childIds
     * @param array<mixed,mixed>                $meta
     */
    public function store(
        array $localizedDetails,
        array $abilities = [],
        array $childIds = [],
        array $meta = [],
        bool $userActivityLog = false,
    ): IType;

    /**
     * @param array{localizedDetails?:array<string,array{title:string}>,abilities?:string[],childIds?:int[],meta?:array<mixed,mixed>} $changes
     */
    public function update(
        int|IType $type,
        array $changes,
        bool $userActivityLog = false
    ): IType;

    public function destroy(int|IType $type, bool $userActivityLog = false): void;
}
