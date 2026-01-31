<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gender;
use App\Enums\IdentificationType;
use App\Enums\MaritalStatus;
use App\Enums\MembershipStatus;
use App\Enums\MemberType;
use App\Enums\Relationship;
use App\Enums\TerminationReason;
use App\Enums\VerificationStatus;
use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Member extends Model implements HasMedia
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    use InteractsWithMedia;

    use SoftDeletes;

    /**
     * The branch that the member belongs to.
     *
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the parent of the member.
     *
     * @return BelongsTo<Member, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    /**
     * Register the media collections for the member.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('identity_documents')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('residential_address_documents')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
            ->singleFile();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'identification_type' => IdentificationType::class,
            'gender' => Gender::class,
            'marital_status' => MaritalStatus::class,
            'member_type' => MemberType::class,
            'membership_status' => MembershipStatus::class,
            'residence_since' => 'date',
            'relationship_type' => Relationship::class,
            'terminated_at' => 'datetime',
            'termination_reason' => TerminationReason::class,
            'verification_status' => VerificationStatus::class,
        ];
    }
}
