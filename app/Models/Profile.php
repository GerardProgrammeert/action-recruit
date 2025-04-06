<?php

namespace App\Models;

use App\Clients\GitHubClient\Enums\UserTypesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @method static Builder isFetched()
 */
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
            'type' => UserTypesEnum::class,
            'is_done' => 'boolean',
            'is_fetched' => 'boolean',
        ];
    }

    public function scopeIsNotFetched(Builder $query,)
    {
        return $query->where('is_fetched', false);
    }

    public function scopeWhereGitHubId(Builder $query, $value)
    {
        return $query->where('github_id', '=', $value);
    }
}
