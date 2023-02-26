<?php

namespace dnj\AAA;

use dnj\AAA\Contracts\IUserManager;
use dnj\AAA\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as AuthUserProvider;

class UserProvider implements AuthUserProvider
{
    public function __construct(protected IUserManager $userManager)
    {
    }

    public function retrieveById($identifier): ?User
    {
        return $this->userManager->find($identifier);
    }

    public function retrieveByToken($identifier, $token): ?User
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
    }

    public function retrieveByCredentials(array $credentials): ?User
    {
        if (!isset($credentials['username'])) {
            return null;
        }

        return $this->userManager->findByUsername($credentials['username']);
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        if (!isset($credentials['username'], $credentials['password'])) {
            return false;
        }
        if (!$user instanceof User) {
            return false;
        }
        $username = $user->usernames()->where('username', $credentials['username'])->first();
        if (!$username) {
            return false;
        }
        $verified = $username->verifyPassword($credentials['password']);
        if ($verified) {
            $user->setActiveUsername($username);
        }

        return $verified;
    }
}
