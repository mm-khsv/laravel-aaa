<?php

namespace dnj\AAA\Models;

use dnj\AAA\Contracts\IHasAbilities;
use dnj\AAA\Contracts\IHasPassword;
use dnj\AAA\Contracts\IUsername;
use dnj\AAA\Database\Factories\UsernameFactory;
use dnj\AAA\Models\Concerns\HasAbilities;
use dnj\UserLogger\Concerns\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * @property int    $id
 * @property int    $user_id
 * @property User   $user
 * @property string $username
 * @property string $password
 */
class Username extends Model implements IUsername, IHasAbilities, IHasPassword
{
    use HasAbilities;
    use Loggable;
    use HasFactory;

    public static function newFactory(): UsernameFactory
    {
        return UsernameFactory::new();
    }

    /**
     * @var string
     */
    protected $table = 'aaa_users_usernames';

    protected $fillable = [
        'user_id',
        'username',
        'password',
    ];

    protected $hidden = ['password'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getId(): int
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
     * @return string[]
     */
    public function getAbilities(): array
    {
        return $this->getUser()->getAbilities();
    }
}
