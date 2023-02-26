<?php
namespace dnj\AAA;

use dnj\AAA\Contracts\IUser;
use dnj\AAA\Contracts\UserStatus;
use dnj\AAA\Contracts\IUserManager;
use Illuminate\Database\MultipleRecordsFoundException;
use Illuminate\Support\Facades\DB;

class UserManager implements IUserManager
{
    public function store(string $name, string $username, string $password, int $typeId): IUser
    {
        $duplicateRecordsCount = Username::where('username', $username)->count();
        if ($duplicateRecordsCount > 0) {
            throw new MultipleRecordsFoundException($duplicateRecordsCount);
        }
        return DB::transaction(function() use ($name, $typeId, $username, $password) {
            $user = User::create([
                'name' => $name,
                'type_id' => $typeId,
                'status' => UserStatus::ACTIVE,
            ]);
            $username = Username::create([
                'user_id' => $user->id,
                'username' => $username,
                'password' => $password,
            ]);
            return $user;
        });
    }
	public function byID(int $userId): ?IUser
    {
        return User::query()
            ->where('id', $userId)
            ->first();
    }
	public function byUsername(string $username): ?IUser
    {
        return Username::where('username', $username)->first();
    }
	public function update(int $userId, ?string $name, ?int $typeId, ?UserStatus $status): IUser
    {

    }
	public function delete(int $userId): void
    {

    }

}