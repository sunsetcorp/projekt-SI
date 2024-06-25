<?php

/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\RegistrationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegistrationController
 */
class RegistrationController extends AbstractController
{
    private RegistrationServiceInterface $registrationService;


    /**
     * Constructor.
     *
     * @param RegistrationServiceInterface $registrationService Registration service
     */
    public function __construct(RegistrationServiceInterface $registrationService)
    {
        $this->registrationService = $registrationService;
    }

        /**
         * Handles the user registration process.
         *
         * @param Request $request HTTP request
         *
         * @return Response HTTP response
         */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->registrationService->register($user, $request)) {
                return $this->redirectToRoute('album_index');
            } else {
                $this->addFlash('error', 'Registration failed.');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
