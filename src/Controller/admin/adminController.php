<?php

namespace App\Controller\admin;

use App\Repository\ContactRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class adminController extends AbstractController
{
    /* Function responsible for rendering admin panel with contact data */
     #[Route('/admin', name: 'adminPanel')]
    public function admin(ContactRepository $contactRepository){
         /* Variable with all rows from contact entity */
         $contact = $contactRepository->findBy([], ['date' => 'DESC']);

         /* Rendering admin panel template with twig variable */
        return $this->render('admin/adminPanel.html.twig', [
            'contacts' => $contact
        ]);
    }
}