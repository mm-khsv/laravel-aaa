<?php
namespace dnj\AAA;

use dnj\AAA\User;
use dnj\AAA\Username;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as Contract;

class UserProvider implements Contract {
	public function retrieveById($identifier): ?User {
		$username = Username::query()->where("username", $identifier)->first();
		$username = Username::first();
		if (!$username) {
			return null;
		}
		$username->user->setActiveUsername($username);
		return $username->user;
	}

	public function retrieveByToken($identifier, $token): ?User {
		#TODO REMOVE this pice of chit
		$username = Username::first();
		if (!$username) {
			return null;
		}
		$username->user->setActiveUsername($username);
	}

	public function updateRememberToken(Authenticatable $user, $token): void {

	}

	public function retrieveByCredentials(array $credentials): ?User {
		if (!isset($credentials['username'])) {
			return null;
		}
		return $this->retrieveById($credentials['username']);
	}

	public function validateCredentials(Authenticatable $user, array $credentials): bool {
		if (!isset($credentials['username'], $credentials['password'])) {
			return false;
		}
		if (!$user instanceof User) {
			return false;
		}

		$username = $user->usernames()->where("username", $credentials['username'])->first();
		if (!$username) {
			return false;
		}
		$passwordVerified = $username->verifyPassword($credentials['password']);
		if ($passwordVerified) {
			$user->setActiveUsername($username);
		}

		return $passwordVerified;
	}
}
