<?php

namespace dnj\AAA\Models;

use dnj\AAA\Contracts\IUser;
use dnj\AAA\Contracts\UserStatus;
use dnj\AAA\Database\Factories\UserFactory;
use dnj\AAA\Models\Concerns\HasAbilities;
use dnj\AAA\Models\Concerns\HasDynamicFields;
use dnj\UserLogger\Concerns\Loggable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string               $name
 * @property int                  $type_id
 * @property array<mixed,mixed>   $meta
 * @property UserStatus           $status
 * @property Type                 $type
 * @property Collection<Username> $usernames
 */
class User extends Model implements IUser, Authenticatable, Authorizable
{
    use HasAbilities;
    use HasDynamicFields;
    use Loggable;
    use HasFactory;

    public static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected ?Username $activeUsername = null;

    protected $casts = [
        'status' => UserStatus::class,
        'meta' => 'array',
    ];

    protected $fillable = [
        'name',
        'type_id',
        'meta',
        'status',
    ];

    /**
     * @var string
     */
    protected $table = 'aaa_users';

    public function setActiveUsername(?Username $activeUsername): void
    {
        $this->activeUsername = $activeUsername;
    }

    public function getActiveUsername(): ?Username
    {
        return $this->activeUsername;
    }

    public function usernames(): HasMany
    {
        return $this->hasMany(Username::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeId(): int
    {
        return $this->type_id;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function getMeta(): array
    {
        return $this->meta ?? [];
    }

    /**
     * @return string[]
     */
    public function getAbilities(): array
    {
        return $this->type->getAbilities();
    }

    public function getAuthIdentifierName(): string
    {
        return $this->getKeyName();
    }

    public function getAuthIdentifier(): int
    {
        return $this->getKey();
    }

    public function getAuthPassword(): string
    {
        return $this->usernames->first()?->password ?? '';
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    /**
     * @param array<string,array{password?:string}> $usernames
     *
     * @return $this
     */
    public function updateUsernames(array $usernames): static
    {
        /**
         * @var \Illuminate\Support\Collection<string,array{password?:string}>
         */
        $usernames = collect($usernames);

        /**
         * @var \Illuminate\Database\Eloquent\Collection<Username>
         */
        $current = $this->usernames;

        // Delete
        $current->filter(fn (Username $u) => $usernames->keys()->doesntContain($u->username))->each(fn (Username $u) => $u->delete());

        // Create
        $usernames->filter(fn ($p, string $username) => $current->doesntContain('username', $username))
            ->each(fn (array $p, string $username) => $this->usernames()->create([
                'username' => $username,
                ...$p,
            ]));

        // Update
        $current->filter(fn (Username $u) => $usernames->keys()->contains($u->username))->each(fn (Username $u) => $u->update($usernames[$u->username]));

        return $this;
    }
}
