<?php

namespace dnj\AAA\Policies;

use dnj\AAA\Contracts\IType;
use dnj\AAA\Contracts\ITypeManager;
use dnj\AAA\Contracts\IUser;
use dnj\AAA\Models\User;
use dnj\AAA\Policy;

class TypePolicy extends Policy
{
    public function getModel(): string
    {
        return IType::class;
    }

    protected function userHasAccessToModel(IUser $user, object $model): ?bool
    {
        if (!$model instanceof IType) {
            throw new \Exception('this policy only works with '.IType::class);
        }
        if ($user instanceof User) {
            /**
             * @var IType
             */
            $type = $user->type;
        } else {
            /**
             * @var ITypeManager
             */
            $typeManager = app(ITypeManager::class);
            $type = $typeManager->findOrFail($user->getTypeId());
        }

        return in_array($model->getId(), $type->getChildIds());
    }
}
