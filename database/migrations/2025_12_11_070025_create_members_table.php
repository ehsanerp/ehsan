<?php

declare(strict_types=1);

use App\Enums\IdentificationType;
use App\Enums\MembershipStatus;
use App\Enums\MemberType;
use App\Enums\VerificationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table): void {
            $table->id()->from(1000);
            $table->string('name');
            $table->string('identity_number');
            $table->string('identification_type')->nullable()->default(IdentificationType::MyKad->value);
            $table->string('residential_address')->nullable();
            $table->string('address_on_identity_card')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('residence_since')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('member_type')->default(MemberType::Resident->value);
            $table->string('membership_status')->default(MembershipStatus::Pending->value);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->nullOnUpdate();
            $table->mediumText('notes')->nullable();
            $table->string('termination_reason')->nullable();
            $table->text('termination_notes')->nullable();
            $table->foreignId('terminated_by')->nullable()->constrained('users')->nullOnDelete()->nullOnUpdate();
            $table->timestamp('terminated_at')->nullable();
            $table->string('verification_status')->default(VerificationStatus::Unverified->value);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->nullOnUpdate();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->nullOnUpdate();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete()->nullOnUpdate();
            $table->foreignId('parent_id')->nullable()->constrained('members')->nullOnDelete()->nullOnUpdate();
            $table->string('relationship_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
