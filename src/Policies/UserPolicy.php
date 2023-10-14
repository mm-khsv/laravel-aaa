<?php

namespace dnj\AAA\Policies;

use dnj\AAA\Contracts\IUser;
use dnj\AAA\Models\User;
use dnj\AAA\Policy;

class UserPolicy extends Policy
{
    public function getModel(): string
    {
        return IUser::class;
    }

    protected function userHasAccessToModel(IUser $user, object $model): ?bool
    {
        if (!$model instanceof IUser) {
            throw new \Exception('this policy only works with '.IUser::class);
        }
        if ($user->getId() == $model->getId()) {
            return true;
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

        return in_array($model->getTypeId(), $type->getChildIds());
    }
}
