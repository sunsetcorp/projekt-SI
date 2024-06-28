<?php

/**
 * Category service.
 */

namespace App\Service;

use App\Repository\CategoryRepository;
use App\Repository\AlbumRepository;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class CategoryService
 *
 * Service class for managing categories.
 */
class CategoryService implements CategoryServiceInterface
{
    private EntityManagerInterface $entityManager;
    private PaginatorInterface $paginator;
    private CategoryRepository $categoryRepository;
    private AlbumRepository $albumRepository;
    /**
         * CategoryService constructor.
         *
         * @param EntityManagerInterface $entityManager      The entity manager
         * @param AlbumRepository        $albumRepository    The album repository
         * @param PaginatorInterface     $paginator          The paginator
         * @param CategoryRepository     $categoryRepository The category repository
         */
    public function __construct(EntityManagerInterface $entityManager, albumRepository $albumRepository, PaginatorInterface $paginator, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->categoryRepository = $categoryRepository;
        $this->albumRepository = $albumRepository;
    }


    /**
     * Get paginated list of categories.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of categories
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate($this->categoryRepository->queryAll(), $page, 10);
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    /**
     * Save entity.
     *
     * @param Category $category Category entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Category $category): void
    {
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
        if (null !== $category->getId()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
        }
        $this->categoryRepository->save($category);
    }


    /**
     * Delete category entity.
     *
     * @param Category $category The category entity to delete
     */
    public function delete(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->albumRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException | NonUniqueResultException) {
            return false;
        }
    }
}
