<?php

/**
 * Admin service interface.
 */

namespace App\Service;

use App\Entity\User;

/**
 * Interface AdminServiceInterface
 *
 * Represents a service interface for administrative operations on users.
 */
interface AdminServiceInterface
{
    /**
     * Retrieves all users from the database.
     *
     * @return User[] The array of User objects representing all users
     */
    public function getAllUsers(): array;

    /**
     * Updates the user entity in the database.
     *
     * @param User $user The user entity to update
     */
    public function updateUser(User $user): void;
}
