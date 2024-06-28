<?php

/**
 * Category controller.
 */

namespace App\Controller;

/**
 * Category controller.
 */


use App\Form\Type\CategoryType;
use App\Entity\Category;
use App\Repository\AlbumRepository;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Service\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class CategoryController.
 */
#[Route('/category')]
class CategoryController extends AbstractController
{
    private CategoryServiceInterface $categoryService;
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService The category service
     * @param TranslatorInterface $translator The translator
     */
    public function __construct(CategoryServiceInterface $categoryService, TranslatorInterface $translator)
    {
        $this->categoryService = $categoryService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param int $page The current page number for pagination
     *
     * @return Response HTTP response
     *
     */
    #[Route(name: 'category_index', methods: ['GET'])]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->categoryService->getPaginatedList($page);
        return $this->render('category/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param CategoryRepository $categoryRepository The category repository
     * @param AlbumRepository $albumRepository The album repository
     * @param int $id The ID of the category to show
     *
     * @return Response HTTP response
     *
     */
    #[Route('/category/{id}', name: 'category_show', methods: ['GET'])]
    public function show(CategoryRepository $categoryRepository, AlbumRepository $albumRepository, int $id): Response
    {
        $category = $categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('The category does not exist');
        }

        $albums = $albumRepository->findBy(['category' => $category]);
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'albums' => $albums,
        ]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'category_create', methods: 'GET|POST', )]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {

        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No access for you!');
        } else {
            $category = new Category();
            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->categoryService->save($category);
                $this->addFlash('success', $this->translator->trans('message.created_successfully'));
                return $this->redirectToRoute('category_index');
            }

            return $this->render('category/create.html.twig', ['form' => $form->createView()]);
        }
    }
    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */

    #[Route('/{id}/edit', name: 'category_edit', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Category $category): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No access for you!');
        } else {
            $form = $this->createForm(CategoryType::class, $category, [
                'method' => 'PUT',
                'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->categoryService->save($category);
                $this->addFlash('success', $this->translator->trans('message.edited_successfully'));
                return $this->redirectToRoute('category_index');
            }

            return $this->render('category/edit.html.twig', [
                'form' => $form->createView(),
                'category' => $category,
            ]);
        }
    }


    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Category $category Category entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'category_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Category $category): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No access for you!');
        } else {
            if (!$this->categoryService->canBeDeleted($category)) {
                $this->addFlash('warning', $this->translator->trans('message.category_contains_tasks'));
                return $this->redirectToRoute('category_index');
            }

            $form = $this->createForm(FormType::class, $category, [
                'method' => 'DELETE',
                'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->categoryService->delete($category);
                $this->addFlash('success', $this->translator->trans('message.deleted_successfully'));
                return $this->redirectToRoute('category_index');
            }

            return $this->render('category/delete.html.twig', [
                'form' => $form->createView(),
                'category' => $category,
            ]);
        }
    }
}
