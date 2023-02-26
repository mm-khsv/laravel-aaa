<?php

namespace dnj\AAA\Models\Concerns;

use Illuminate\Auth\Access\Response;

trait HasAbilities
{
    /**
     * @param iterable|string $abilities
     * @param array|mixed     $arguments
     */
    public function can($ability, $arguments = []): bool
    {
        if (is_iterable($ability)) {
            return $this->canAll(iterator_to_array($ability));
        }

        return in_array($ability, $this->getAbilities());
    }

    /**
     * @param string[] $abilities
     */
    public function canAll(array $abilities): bool
    {
        $currentAbilities = $this->getAbilities();
        foreach ($abilities as $ability) {
            if (!in_array($ability, $currentAbilities)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string[] $abilities
     */
    public function canAny(array $abilities): bool
    {
        $currentAbilities = $this->getAbilities();
        foreach ($abilities as $ability) {
            if (in_array($ability, $currentAbilities)) {
                return true;
            }
        }

        return false;
    }

    public function cant(string $ability): bool
    {
        return !$this->can($ability);
    }

    public function authorize(string $ability): void
    {
        if ($this->cant($ability)) {
            Response::deny()->authorize();
        }
    }
}
