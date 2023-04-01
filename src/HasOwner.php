<?php

namespace dnj\AAA;

use dnj\AAA\Contracts\ITypeManager;
use dnj\AAA\Contracts\IUser;
use dnj\AAA\Contracts\IUserManager;
use dnj\AAA\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

trait HasOwner
{
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getOwnerUserId(): int
    {
        return $this->owner_id;
    }

    public function getOwnerUserColumn(): string
    {
        return 'owner_id';
    }

    public function scopeUserHasAccess(Builder $query, int|IUser $user): void
    {
        if (is_int($user)) {
            /**
             * @var IUserManager
             */
            $userManager = app(IUserManager::class);
            $user = $userManager->findOrFail($user);
        }
        if (!$user instanceof Model) {
            throw new \Exception('This method just work with '.Model::class);
        }
        /**
         * @var ITypeManager
         */
        $typeManager = app(ITypeManager::class);
        $type = $typeManager->findOrFail($user->getTypeId());
        $childIds = $type->getChildIds();

        if ($childIds) {
            $query->where(function ($query) use ($user, $childIds) {
                $userTable = $user->getTable();
                $query->whereExists(function ($query) use ($userTable, $childIds) {
                    $query->select(DB::raw(1))
                        ->from($userTable)
                        ->whereColumn($this->getTable().'.'.$this->getOwnerUserColumn(), "{$userTable}.id")
                        ->whereIn("{$userTable}.type_id", $childIds);
                });
                $query->orWhere($this->getOwnerUserColumn(), $user->getId());
            });
        } else {
            $query->where($this->getOwnerUserColumn(), $user->getId());
        }
    }

    public function hasUserAccess(int|IUser $user): bool
    {
        if (User::ensureId($user) == $this->getOwnerUserId()) {
            return true;
        }
        /**
         * @var IUserManager
         */
        $userManager = app(IUserManager::class);

        return $userManager->isParentOf($user, $this->getOwnerUserId());
    }
}
