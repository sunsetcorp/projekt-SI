<?php

/**
 * Admin service.
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Service for administrative operations on users.
 */
class AdminService implements AdminServiceInterface
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;


    /**
     * AdminService constructor.
     *
     * @param EntityManagerInterface      $entityManager  The entity manager for database operations
     * @param UserPasswordHasherInterface $passwordHasher The password hasher for hashing user passwords
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Retrieves all users from the database.
     *
     * @return User[] The array of User objects representing all users
     */
    public function getAllUsers(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    /**
     * Updates the user entity in the database.
     *
     * @param User $user The user entity to update
     */
    public function updateUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Updates the password of a user entity and persists it.
     *
     * @param User   $user          The user entity for which to update the password
     * @param string $plainPassword The plain password to hash and set for the user
     */
    public function updateUserPassword(User $user, string $plainPassword): void
    {
        $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->updateUser($user);
    }
}
