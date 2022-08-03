<?php

namespace App\Controller\landing;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class accessDeny extends AbstractController
{
    /* Function responsible for rendering access deny template */
    #[Route('/access_deny', name: 'accessDeny')]
    public function accessDeny(){
        /* Rendering access deny template */
        return $this->render('landing/accessDeny.html.twig');
    }
}