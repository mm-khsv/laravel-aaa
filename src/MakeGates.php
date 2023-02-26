<?php
namespace dnj\AAA;

use dnj\AAA\Contracts\ITypeManager;
use dnj\AAA\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

trait MakeGates {
	public function integrateToGates() {
		Gate::after([$this, "runAfterGate"]);
	}

	public function runAfterGate(?User $user = null, string $ability, Response|bool|null $response) {
		if (!is_null($response)) {
			return $response;
		}

		if (Gate::has($ability)) {
			return null;
		}
		if ($user) {
			return $user->can($ability);
		}
		
		/**
		 * @var ITypeManager
		 */
		$typeManager = $this->app->get(TypeManager::class);
		$guestType = $typeManager->getGuestType();
		if ($guestType == null) {
			return $guestType->can($ability);
		}

		return null;
	}
}
