<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BranchFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Branch extends Model implements HasAvatar, HasMedia
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory;

    use InteractsWithMedia;

    use LogsActivity;

    /**
     * Get the users that belong to the branch.
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_has_users');
    }

    /**
     * Indicate if the branch is the primary branch.
     */
    public function isPrimary(): bool
    {
        return $this->is_primary ?? false;
    }

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'is_primary'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName): string => __('The branch has been :event', ['event' => $eventName]));
    }

    /**
     * Get the avatar URL for Filament.
     */
    public function getFilamentAvatarUrl(): string
    {
        return filled($this->getFirstMediaUrl('branch_logo')) ? $this->getFirstMediaUrl('branch_logo') : $this->defaultAvatarUrl();
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('branch_logo')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();
    }

    /**
     * The "booted" method of the model.
     */
    #[Override]
    protected static function booted(): void
    {
        self::saved(function (Branch $branch): void {
            Branch::query()->when($branch->isPrimary(), fn ($query) => $query->whereNot('id', $branch->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]));
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Get the default avatar URL.
     */
    private function defaultAvatarUrl(): string
    {
        $name = mb_trim(collect(explode(' ', ($this->name ?? 'Branch')))->map(fn ($segment): string => mb_substr($segment, 0, 1))->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&length=2&color=FFFFFF&background=999999';
    }
}
