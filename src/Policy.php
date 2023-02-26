<?php
namespace dnj\AAA;

use dnj\AAA\Contracts\IUser;
use dnj\AAA\Contracts\IOwnerableModel;
use dnj\AAA\Contracts\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\Response;

abstract class Policy
{
    public function __construct()
    {
        //
    }

    public function __call(string $method, array $arguments)
    {
        return $this->accessProcessor(
            $method,
            $this->getUserFromArgs($arguments),
            $this->getModelFromArgs($arguments)
        );
    }

    protected function accessProcessor(string $ability, ?IUser $user, ?Model $model): ?Response
    {
        if (!$user or !$model) {
            return null;
        }
        if (!$user->can($ability)) {
            return $this->denyResponseWhenUserCant($ability, $user);  
        }
        if ($this->userHasAccessToModel($user, $model)) {
            return $this->allowResponse($ability, $user, $model);
        } else {
            return $this->denyResponse($ability, $user, $model);
        }

        return null;
    }

    protected function userHasAccessToModel(IUser $user, Model $model): ?bool
    {
        $ownerUser = $model->getOwnerUser();
        if (!$ownerUser) {
            return null;
        }
        if ($user->getID() == $ownerUser->getID()) {
            return true;
        }

        return $user->getType()->getChildrenIds()->contains($ownerUser->getTypeId());
    }

    protected function allowResponse(string $ability, IUser $user, Model $model): Response
    {
        return Response::allow(
            sprintf("Congratulation '%s', you has access to '%s' ability on: %s", $user->getName(), $ability, $model->__toString())
        );
    }

    protected function denyResponse(string $ability, IUser $user, Model $model): Response
    {
        return Response::denyAsNotFound(
            sprintf("Sorry '%s', You has no access to ability '%s'", $user->getName(), $ability)
        );
    }

    protected function denyResponseWhenUserCant(string $ability, IUser $user): Response
    {
        return Response::denyWithStatus(403, sprintf("Sorry '%s', You has no access to ability '%s'", $user->getName(), $ability));
    }

    private function getUserFromArgs(array $arguments): ?IUser
    {
        $index = 0;
        if (!isset($arguments[$index]) or !$arguments[$index]) {
            return null;
        }
        if (!$arguments[$index] instanceof IUser) {
            throw new InvalidArgumentException(
                sprintf('The argument #%s should be instance of %s but %s given!', $index + 1, IUser::class, get_class($arguments[$index]))
            );
        }

        return $arguments[$index];
    }

    private function getModelFromArgs(array $arguments): ?Model
    {
        $index = 1;
        if (!isset($arguments[$index]) or !$arguments[$index]) {
            return null;
        }
        if (!is_object($arguments[$index])) {
            throw new InvalidArgumentException(sprintf('The argument #%s should be object, but %s given!', $index + 1, gettype($arguments[$index])));
        }
        if (!$arguments[$index] instanceof Model) {
            throw new InvalidArgumentException(
                sprintf('The argument #%s should be instance of %s but %s given!', $index + 1, Model::class, get_class($arguments[$index]))
            );
        }
        if (!$arguments[$index] instanceof IOwnerableModel) {
            throw new InvalidArgumentException(sprintf('The given model (%s) is not implemented the (%s) interface!', get_class($arguments[$index]), IOwnerableModel::class));
        }

        return $arguments[$index];
    }
}