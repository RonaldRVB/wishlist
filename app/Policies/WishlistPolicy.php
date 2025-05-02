<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;

class WishlistPolicy
{
    public function create(User $user)
    {
        // On autorise tous les utilisateurs connectés à créer une wishlist
        return true;
    }
    public function viewAny(User $user)
    {
        // Permet à tous les utilisateurs connectés d'accéder à la liste des wishlists
        return true;
    }

    /**
     * Autorise uniquement le propriétaire à voir la wishlist.
     */
    public function view(User $user, Wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id;
    }

    /**
     * Autorise uniquement le propriétaire à modifier.
     */
    public function update(User $user, Wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id;
    }

    /**
     * Interdit la suppression si c’est la liste personnelle.
     */
    public function delete(User $user, Wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id && $wishlist->title !== 'Ma liste personnelle';
    }

    /**
     * Autorise la gestion des gifts liés à la wishlist.
     */
    public function manageGifts(User $user, Wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id;
    }
}
