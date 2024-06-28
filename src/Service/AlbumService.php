<?php

/**
 * Album service.
 */

namespace App\Service;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AlbumService.
 */
class AlbumService implements AlbumServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;
    /**
         * Constructor.
         *
         * @param AlbumRepository    $albumRepository Album repository
         * @param PaginatorInterface $paginator       Paginator
         */
    public function __construct(private readonly AlbumRepository $albumRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate($this->albumRepository->queryAll(), $page, self::PAGINATOR_ITEMS_PER_PAGE);
    }

    /**
     * Save entity.
     *
     * @param Album $album Album entity
     */
    public function save(Album $album): void
    {
        $this->albumRepository->save($album);
    }

    /**
     * Delete entity.
     *
     * @param Album $album Album entity
     */
    public function delete(Album $album): void
    {
        $this->albumRepository->delete($album);
    }
}
