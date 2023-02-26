<?php

namespace dnj\AAA\Contracts;

enum UserStatus: int
{
    case ACTIVE = 1;
    case SUSPEND = 2;
}
