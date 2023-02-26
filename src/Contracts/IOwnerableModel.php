<?php
namespace dnj\AAA\Contracts;

interface IOwnerableModel 
{
    public function getOwnerUser(): ?IUser;
    public function getOwnerUserColumn(): string;
}
