<?php

namespace dnj\AAA;

use Illuminate\Auth\Access\Response;

trait HasAbilities
{
    public function can(string $ability): bool
    {
        return $this->getAbilities()->contains($ability);
    }

    /**
     * @param string[] $abilities
     */
    public function canAll(array $abilities): bool
    {
        $currentAbilities = $this->getAbilities();

        return collect($abilities)
            ->every(fn (string $a) => $currentAbilities->contains($a));
    }

    /**
     * @param string[] $abilities
     */
    public function canAny(array $abilities): bool
    {
        $currentAbilities = $this->getAbilities();

        return collect($abilities)
            ->some(fn (string $a) => $currentAbilities->contains($a));
    }

    public function cant(string $ability): bool
    {
        return $this->can($ability);
    }

    public function authorize(string $ability): void
    {
        if ($this->cant($ability)) {
            Response::deny()->authorize();
        }
    }
}
