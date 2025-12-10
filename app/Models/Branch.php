<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

final class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory;

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
}
