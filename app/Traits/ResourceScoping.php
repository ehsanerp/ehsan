<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Branch;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait ResourceScoping
{
    /**
     * Apply resource scoping to the query based on user permissions
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public static function applyResourceScoping(Builder $query): Builder
    {
        $user = Auth::user();

        if (! $user) {
            return $query;
        }

        $permissionScope = session('user_permission_scope');

        return match ($permissionScope) {
            'group' => static::applyBranchScoping($query),
            'individual' => static::applyIndividualScoping($query),
            // Global permission or no scoping needed
            default => $query,
        };
    }

    /**
     * Apply branch-level scoping to the query
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected static function applyBranchScoping(Builder $query): Builder
    {
        $allowedBranchIds = session('user_allowed_branch_ids', []);

        if (empty($allowedBranchIds)) {
            // If no allowed branches, return empty result
            return $query->whereRaw('1 = 0');
        }

        // Check if the model has a branch_id column
        $model = $query->getModel();
        $table = $model->getTable();

        if ($model->getConnection()->getSchemaBuilder()->hasColumn($table, 'branch_id')) {
            return $query->whereIn('branch_id', $allowedBranchIds);
        }

        // If no branch_id column, check for branch relationship
        if (method_exists($model, 'branch')) {
            return $query->whereHas('branch', function (Builder $branchQuery) use ($allowedBranchIds): void {
                $branchQuery->whereIn('id', $allowedBranchIds);
            });
        }

        return $query;
    }

    /**
     * Apply individual-level scoping to the query
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected static function applyIndividualScoping(Builder $query): Builder
    {
        $userId = session('user_scoped_user_id');

        if (! $userId) {
            return $query->whereRaw('1 = 0');
        }

        $model = $query->getModel();
        $table = $model->getTable();

        // Check if the model has a user_id column
        if ($model->getConnection()->getSchemaBuilder()->hasColumn($table, 'user_id')) {
            $query = $query->where('user_id', $userId);
        }

        // Also apply branch scoping if applicable
        $allowedBranchIds = session('user_allowed_branch_ids', []);
        if (! empty($allowedBranchIds)) {
            if ($model->getConnection()->getSchemaBuilder()->hasColumn($table, 'branch_id')) {
                $query = $query->whereIn('branch_id', $allowedBranchIds);
            } elseif (method_exists($model, 'branch')) {
                $query = $query->whereHas('branch', function (Builder $branchQuery) use ($allowedBranchIds): void {
                    $branchQuery->whereIn('id', $allowedBranchIds);
                });
            }
        }

        return $query;
    }

    /**
     * Apply tenant-based scoping (existing Filament tenant functionality)
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected static function applyTenantScoping(Builder $query): Builder
    {
        $tenant = Filament::getTenant();

        if ($tenant instanceof Branch && ! $tenant->is_primary) {
            $model = $query->getModel();
            $table = $model->getTable();

            if ($model->getConnection()->getSchemaBuilder()->hasColumn($table, 'branch_id')) {
                $query = $query->where('branch_id', $tenant->id);
            }
        }

        return $query;
    }
}
