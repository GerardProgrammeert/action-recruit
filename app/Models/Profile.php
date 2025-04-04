<?php

namespace App\Models;

use App\Services\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'github_id',
        'type',
        'url',
        'html_url',
        'location',
        'hireable',
        'name',
        'email',
        'twitter_username',
        'blog',
        'is_done',
        'is_fetched',
        'linkedin_links',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'github_id' => 'integer',
            'linkedin_links' => 'array',
            'type' => UserType::class,
            'is_done' => 'boolean',
            'is_fetched' => 'boolean',
        ];
    }
}
