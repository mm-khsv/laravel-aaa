<?php
namespace dnj\AAA\Contracts;

interface IUserManager {
	public function store(string $name, string $username, string $password, int $typeId): IUser;
	public function byID(int $userId): ?IUser;
	public function byUsername(string $username): ?IUser;
	public function update(int $userId, ?string $name, ?int $typeId, ?UserStatus $status): IUser;
	public function delete(int $userId): void;
}
