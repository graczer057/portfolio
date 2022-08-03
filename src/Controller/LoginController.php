<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /* Function responsible for login */
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        /* Variable containing error/s during login process */
        $error = $authenticationUtils->getLastAuthenticationError();

        /* Variable getting data to provide user */
        $lastUsername = $authenticationUtils->getLastUsername();

        /* Rendering login template with twig variables */
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /* Function only containing app route and name all processes are executing from config files */
    #[Route('/logout', name: 'logout')]
    public function logout(){}
}
