<?php

namespace App\Clients\GitHubClient\Enums;

enum UserTypeEnum: string
{
    case USER = 'User';
    case ORGANIZATION = 'Organization';
}
