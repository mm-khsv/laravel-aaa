<?php

namespace dnj\AAA;

use dnj\AAA\Contracts\IType;
use dnj\AAA\HasAbilities;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Type extends Model implements IType
{
    use HasAbilities;

    /**
     * @var string
     */
    protected $table = 'aaa_types';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function abilities()
    {
        return $this->hasMany(TypeAbility::class);
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getLocalizedDetails(string $lang): ?TypeLocalizedDetails
    {
        return TypeLocalizedDetails::query()
            ->where('lang', $lang)
            ->where('type_id', $this->getID())
            ->first();
    }

    /**
     * @return Collection<string>
     */
    public function getAbilities(): Collection
    {
        return TypeAbility::query()
            ->where('type_id', $this->getID())
            ->get(['name'])
            ->pluck('name');
    }

    /**
     * @return Collection<int>
     */
    public function getChildrenIds(): Collection
    {
        return $this->getChildren()->pluck('id');
    }

    /**
     * @return Collection<Type>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
