<?php

namespace dnj\AAA;

use dnj\AAA\Contracts\IType;
use dnj\AAA\Contracts\IUser;
use dnj\AAA\Contracts\UserStatus;
use dnj\AAA\HasAbilities;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class User extends Model implements IUser, Authenticatable
{
    use HasAbilities, HasApiTokens;


    protected ?Username $activeUsername = null;

    protected $casts = [
        'status' => UserStatus::class,
    ];

    protected $fillable = [
        'name',
        'type_id',
        'status',
    ];


    public $timestamps = false;

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

    public function usernames()
    {
        return $this->hasMany(Username::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function getID(): int
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

    public function getType(): Type
    {
        return $this->type;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    /**
     * @return Collection<string>
     */
    public function getAbilities(): Collection
    {
        return $this->getType()->getAbilities();
    }


    public function getAuthIdentifierName(): string
    {
        return "";
    }

    public function getAuthIdentifier(): string
    {
        return "";
    }

    public function getAuthPassword(): string
    {
        return "";
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
        return "";
    }
}
