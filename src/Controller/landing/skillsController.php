<?php

namespace App\Controller\landing;

use App\Repository\SkillsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class skillsController extends AbstractController
{
    /* Function responsible for rendering skills template*/
     #[Route('/skills', name:'skills')]
    public function skill(SkillsRepository $skillsRepository){
         /* Creating variable with all skills rows from entity order by name ascendingly */
        $skills = $skillsRepository->findBy([], ['name' => 'ASC']);

        /* Rendering a skill template with twig variable */
        return $this->render('landing/skills.html.twig', [
            'skills' => $skills
        ]);
    }
}