<?php

namespace App\Policies;

use App\Models\StockLog;
use App\Models\User;

class StockLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'supplier']);
    }

    public function view(User $user, StockLog $stockLog): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('supplier') && $user->supplier?->id === $stockLog->product->supplier_id;
    }

    public function create(User $user): bool
    {
        return false; // Only created via StockService
    }

    public function update(User $user, StockLog $stockLog): bool
    {
        return false; // Stock logs are immutable
    }

    public function delete(User $user, StockLog $stockLog): bool
    {
        return false; // Stock logs are immutable
    }
}
