<?php

namespace dnj\AAA\Contracts;

enum UserStatus: string
{
    case ACTIVE = 'ACTIVE';
    case SUSPEND = 'SUSPEND';
}
