<?php

namespace App\Clients\GitHubClient\Enums;

enum EndpointsEnum: string
{
    case SEARCH_USERS = '/search/users';
    case USERS = '/users';
}
