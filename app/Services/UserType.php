<?php

namespace App\Services;

enum UserType: string
{
    case USER = 'User';
    case ORGANIZATION = 'Organization';
}
