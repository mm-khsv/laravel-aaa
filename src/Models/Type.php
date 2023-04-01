<?php

namespace dnj\AAA\Models;

use dnj\AAA\Contracts\IType;
use dnj\AAA\Database\Factories\TypeFactory;
use dnj\AAA\Models\Concerns\HasAbilities;
use dnj\UserLogger\Concerns\Loggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int                              $id
 * @property Collection<TypeLocalizedDetails> $translates
 * @property Collection<TypeAbility>          $abilities
 * @property Collection<User>                 $users
 * @property Collection<self>                 $children
 * @property Collection<self>                 $parents
 * @property array<mixed,mixed>               $meta
 */
class Type extends Model implements IType
{
    use HasAbilities;
    use Loggable;
    use HasFactory;

    public static function newFactory(): TypeFactory
    {
        return TypeFactory::new();
    }

    public static function ensureId(int|IType $value): int
    {
        return $value instanceof IType ? $value->getId() : $value;
    }

    /**
     * @var string
     */
    protected $table = 'aaa_types';

    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = [
        'meta',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function abilities()
    {
        return $this->hasMany(TypeAbility::class);
    }

    public function translates()
    {
        return $this->hasMany(TypeLocalizedDetails::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLocalizedDetails(string $lang): ?TypeLocalizedDetails
    {
        return TypeLocalizedDetails::query()
            ->where('lang', $lang)
            ->where('type_id', $this->getId())
            ->first();
    }

    /**
     * @return string[]
     */
    public function getAbilities(): array
    {
        return $this->abilities->pluck('name')->all();
    }

    /**
     * @return int[]
     */
    public function getChildIds(): array
    {
        return $this->getChildren()->pluck('id')->all();
    }

    /**
     * @return int[]
     */
    public function getParentIds(): array
    {
        return $this->getParents()->pluck('id')->all();
    }

    /**
     * @return Collection<Type>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @return Collection<Type>
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'aaa_types_children', 'parent_id', 'child_id');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'aaa_types_children', 'child_id', 'parent_id');
    }

    public function getMeta(): array
    {
        return $this->meta ?? [];
    }

    /**
     * @param string[] $newAbilities
     */
    public function updateAbilities(array $newAbilities): static
    {
        $newAbilities = collect($newAbilities);
        $current = $this->abilities->pluck('name');
        $created = $newAbilities->diff($current);
        $deleted = $current->diff($newAbilities);
        if ($deleted) {
            $this->abilities()->getQuery()->whereIn('name', $deleted)->delete();
        }
        if ($created) {
            $this->abilities()->createMany($created->map(fn (string $name) => ['name' => $name]));
        }

        return $this;
    }

    /**
     * @param array<string,array{title:string}> $localizedDetails
     */
    public function updateLocalizedDetails(array $localizedDetails): static
    {
        $translates = $this->translates;
        $newLangs = array_keys($localizedDetails);

        $translates->filter(fn (TypeLocalizedDetails $t) => !in_array($t->lang, $newLangs))->each(fn (TypeLocalizedDetails $t) => $t->delete());

        $newTranslates = array_diff($newLangs, $translates->pluck('lang')->all());
        if ($newTranslates) {
            $newTranslates = $this->translates()->createMany(array_map(fn (string $lang) => [
                'lang' => $lang,
                ...$localizedDetails[$lang],
            ], $newTranslates));
        }

        $translates->filter(fn (TypeLocalizedDetails $t) => isset($localizedDetails[$t->lang]))->each(fn (TypeLocalizedDetails $t) => $t->update($localizedDetails[$t->lang]));

        return $this;
    }

    /**
     * @param int[] $childIds
     */
    public function updateChildIds(array $childIds): static
    {
        $this->children()->sync($childIds);

        return $this;
    }

    public function isParentOf(int|IType $other): bool
    {
        return $this->children->pluck('id')->contains(self::ensureId($other));
    }

    public function isChildOf(int|IType $other): bool
    {
        return $this->parents->pluck('id')->contains(self::ensureId($other));
    }
}
