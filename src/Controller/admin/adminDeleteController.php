<?php

namespace App\Controller\admin;

use App\Repository\ExpRepository;
use App\Repository\PorPicRepository;
use App\Repository\PortfolioRepository;
use App\Repository\SkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class adminDeleteController extends AbstractController
{
    /* Function responsible only for displaying all rows from each tables */
    #[Route('/admin/delete', name: 'adminDelete')]
    public function adminDelete(PortfolioRepository $portfolioRepository, PorPicRepository $porPicRepository, SkillsRepository $skillsRepository, ExpRepository $expRepository){

         /* Creating variables with all rows from each tables */
         $pros = $portfolioRepository->findAll();
         $pics = $porPicRepository->findAll();
         $skills = $skillsRepository->findAll();
         $exps = $expRepository->findAll();

         /* Rendering a template with twig variables */
         return $this->render('admin/adminDelete.html.twig', [
             'pros' => $pros,
             'pics' => $pics,
             'skills' => $skills,
             'exps' => $exps
         ]);
    }

    /* Function responsible for deleting an existing projects from portfolio and pictures with them */
    #[Route('/admin/delete/pro/{proId}', name: 'proDelete')]
    public function porDelete(int $proId, PortfolioRepository $portfolioRepository, PorPicRepository $porPicRepository, EntityManagerInterface $entityManager){

        /* Creating variables with specific row from each tables */
        $pro = $portfolioRepository->findOneBy(['id' => $proId]);
        $pic = $porPicRepository->findBy(['portfolio' => $proId]);

        /* Checking if project is still existing in the entity */
        if(is_null($pro)){
            /* Rendering warning alert if it is true */
            $this->addFlash('warning', 'Sorry adminku, nie mamy takiego projektu :(');
        }

        /* Removing row from entity */
        $entityManager->remove($pro);

        /* For each pictures from the array this loop deleting picture from directory and entity */
        foreach ($pic as $picture) {
            $fileName = $picture->getPicture();

            $fileSystem = new Filesystem();
            $fileSystem->remove($fileName);

            $entityManager->remove($picture);
        }

        /* Flushing all of objects */
        $entityManager->flush();

        /* Generating success alert */
        $this->addFlash('success', 'Udało się usunąć projekt ;)');

        /* Rendering admin delete template */
        return $this->redirectToRoute('adminDelete');
    }

    /* Function responsible for deleting an existing picture */
    #[Route('/admin/delete/pic/{picId}', name: 'picDelete')]
    public function picDelete(int $picId, PorPicRepository $porPicRepository, EntityManagerInterface $entityManager){
        /* Variable containing an object */
        $pic = $porPicRepository->findOneBy(['id' => $picId]);

        /* Finding all picture linked to same portfolio project*/
        $picture = $pic->getPictureOrder();
        $id = $pic->getPortfolio()->getId();
        $pictures = $porPicRepository->findBy(['portfolio' => $id]);

        /* Creating new filesystem */
        $fileSystem = new Filesystem();

        /* Removing picture from directory */
        $fileSystem->remove('images/'.$pic->getPicture());

        /* And removing picture from entity */
        $entityManager->remove($pic);

        /* Changing picture order to all pictures from entity linked to the same portfolio project after deleting specific picture */
        foreach ($pictures as $pict){
            $picturesOrder = $pict->getPictureOrder();
            if($picturesOrder > $picture){
                $picturesOrder--;
                $pict->setPictureOrder($picturesOrder);
            }
        }

        /* Flushing all the data */
        $entityManager->flush();

        /* Generating success alert */
        $this->addFlash('success', 'Udało się usunąć picture ;)');

        /* Rendering admin delete template */
        return $this->redirectToRoute('adminDelete');
    }

    /**
     * @Route("/admin/delete/skill/{skillId}", name="skillDelete")
     */
    public function skillDelete(int $skillId, SkillsRepository $skillsRepository, EntityManagerInterface $entityManager){
        $skill = $skillsRepository->findOneBy(['id' => $skillId]);

        if(is_null($skill)){
            $this->addFlash('warning', 'Sorry adminku, nie mamy takiego skilla :(');
        }

        $entityManager->remove($skill);
        $entityManager->flush();

        /* Generating success alert */
        $this->addFlash('success', 'Udało się usunąć skilla ;)');

        /* Rendering admin delete template */
        return $this->redirectToRoute('adminDelete');
    }

    /**
     * @Route("/admin/delete/exp/{expId}", name="expDelete")
     */
    public function expDelete(int $expId, ExpRepository $expRepository, EntityManagerInterface $entityManager){
        $exp = $expRepository->findOneBy(['id' => $expId]);

        if(is_null($exp)){
            $this->addFlash('warning', 'Sorry adminku, nie mamy takiego expa :(');
        }

        $entityManager->remove($exp);
        $entityManager->flush();

        /* Generating success alert */
        $this->addFlash('success', 'Udało się usunąć expa ;)');

        /* Rendering admin delete template */
        return $this->redirectToRoute('adminDelete');

    }
}