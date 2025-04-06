<?php

namespace App\Clients\GitHubClient\Enums;

enum UserTypesEnum: string
{
    case USER = 'User';
    case ORGANIZATION = 'Organization';
}
