<?php
namespace dnj\AAA;

use dnj\AAA\Contracts\ITypeManager;
use dnj\AAA\Type;

class TypeManager implements ITypeManager {
	public function getGuestTypeID(): ?int {
		return config("aaa.guestType");
	}

	public function getGuestType(): ?Type
	{
		$id = $this->getGuestTypeID();
		if ($id === null) {
			return $id;
		}
		return $this->getByID($id);
	}

	public function getByID(int $id): Type {
		return Type::query()->findOrFail($id);
	}
}
