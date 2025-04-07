<?php

namespace App\Enums;

enum ProfileStatusEnum: string
{
    case UNPROCESSED = 'unprocessed';
    case GITHUB_ENRICHED = 'github_enriched';
    case GOOGLE_ENRICHED = 'google_enriched';
}
