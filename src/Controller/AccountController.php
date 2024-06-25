<?php

/**
 * Account controller.
 */

namespace App\Controller;

use App\Service\AccountServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
*
 */
class AccountController extends AbstractController
{
    private AccountServiceInterface $accountService;


    /**
     * AccountController constructor.
     *
     * @param AccountServiceInterface $accountService The account service used for business logic
     */
    public function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Render the account page.
     *
     * @return Response The response object
     *
     * @Route("/account", name="app_account")
     */
    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {
        return $this->accountService->renderAccountPage();
    }

    /**
     * Handle account edit request.
     *
     * @param Request $request The HTTP request object
     *
     * @return Response The response object
     *
     * @Route("/account/edit", name="app_account_edit")
     */
    #[Route('/account/edit', name: 'app_account_edit')]
    public function edit(Request $request): Response
    {
        $response = $this->accountService->handleAccountEdit($request);


        if ($request->isMethod('POST') && $response instanceof RedirectResponse) {
            $this->addFlash('success', 'Account updated successfully.');
        }

        return $response;
    }
}
