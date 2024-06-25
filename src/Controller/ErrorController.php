<?php

/**
 * Error controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ErrorController
 */
class ErrorController extends AbstractController
{
     /**
      * Renders the access denied page.
      *
      * @return Response HTTP response
      */
    #[Route('/access-denied', name: 'access_denied')]
    public function accessDenied(): Response
    {
        return $this->render('error/accessdenied.html.twig');
    }
}
