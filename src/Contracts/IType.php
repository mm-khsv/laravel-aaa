<?php

namespace dnj\AAA\Contracts;

interface IType extends IHasAbilities
{
    public function getID(): int;

    public function getLocalizedDetails(string $lang): ?ITypeLocalizedDetails;

    /**
     * @return iterable<string>
     */
    public function getAbilities(): iterable;

    /**
     * @return iterable<int[>
     */
    public function getChildrenIds(): iterable;

    /**
     * @return iterable<IType>
     */
    public function getChildren(): iterable;
}
