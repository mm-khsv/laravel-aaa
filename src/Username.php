<?php

namespace dnj\AAA;

use dnj\AAA\Contracts\IHasAbilities;
use dnj\AAA\Contracts\IUsername;
use dnj\AAA\HasAbilities;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property Contracts\IUser $user
 * @property string $username
 * @property string $password
 */
class Username extends Model implements IUsername, IHasAbilities
{
    use HasAbilities;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($username) {
            $username->password = Hash::make($username->password);
        });
    }

    /**
     * @var string
     */
    protected $table = 'aaa_users_usernames';

    /**
     * @var array<string,mixed>
     */
    protected $casts = [
        // 'password' => 'encrypted',
    ];

    protected $fillable = [
        'user_id',
        'username',
        'password',
    ];

    protected $hidden = ['password'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getUserID(): int
    {
        return $this->user_id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * @return Collection<string>
     */
    public function getAbilities(): Collection
    {
        return $this->getUser()->getAbilities();
    }
}
