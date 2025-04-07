<?php

namespace App\Models;

use App\Clients\GitHubClient\Enums\UserTypesEnum;
use App\Enums\ProfileStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @method static Builder withStatus()
 * @method static Builder GitHubId()
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
        'status',
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
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'github_id' => 'integer',
            'status' => ProfileStatusEnum::class,
            'linkedin_links' => 'array',
            'type' => UserTypesEnum::class,
        ];
    }

    public function scopeWithStatus(Builder $query, ProfileStatusEnum $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeGitHubId(Builder $query, string $value)
    {
        return $query->where('github_id', '=', $value);
    }
}
