<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'supplier']);
    }

    public function view(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('supplier') && $this->ownsProduct($user, $product);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'supplier']);
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('supplier') && $this->ownsProduct($user, $product);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->hasRole('admin');
    }

    private function ownsProduct(User $user, Product $product): bool
    {
        return $user->supplier?->id === $product->supplier_id;
    }
}
