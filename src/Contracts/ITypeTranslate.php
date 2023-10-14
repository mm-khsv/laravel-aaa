<?php

namespace dnj\AAA\Contracts;

use dnj\Localization\Contracts\ITranslateModel;

interface ITypeTranslate extends ITranslateModel
{
    public function getTypeID(): int;

    public function getTitle(): string;
}
