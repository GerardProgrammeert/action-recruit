<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
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
        'linkedin_links',
        'is_fetched',
    ];

    protected $casts = [
        'linkedin_links' => 'array',
    ];
}
