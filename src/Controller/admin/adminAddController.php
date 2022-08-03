<?php

namespace App\Controller\admin;

use App\Entity\Exp;
use App\Entity\PorPic;
use App\Entity\Portfolio;
use App\Entity\Skills;
use App\Forms\adminPanel\experienceAddType;
use App\Forms\adminPanel\portfolioAddType;
use App\Forms\adminPanel\portfolioPictureAddType;
use App\Forms\adminPanel\skillsAddType;
use App\Repository\PorPicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
class adminAddController extends AbstractController
{
    /* Creating a function with 4 forms and rendering template */
    #[Route('/admin/add', name: 'adminAdd')]
    public function adminAdd(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PorPicRepository $porPicRepository){
        /* Creating variables to create for each form and handle request */
        $proForm = $this->createForm(portfolioAddType::class);
        $proForm->handleRequest($request);

        $picForm = $this->createForm(portfolioPictureAddType::class);
        $picForm->handleRequest($request);

        $skillForm = $this->createForm(skillsAddType::class);
        $skillForm->handleRequest($request);

        $expForm = $this->createForm(experienceAddType::class);
        $expForm->handleRequest($request);

        /* If statement for first form that is valid and submitted */
        if($proForm->isSubmitted() && $proForm->isValid()){
            /* Variable containing all the data from form */
            $proFormData = $proForm->getData();

            /* Creating new row in the portfolio entity */
            $pro = new Portfolio(
                $proFormData['mainTitle'],
                $proFormData['worldLink'],
                $proFormData['githubLink'],
                $proFormData['description'],
                $proFormData['stack']
            );

            /* Persist tells to doctrine to "manage" object, but to create query is flush responsibility because this method looking through all object to be persisted to database/entity if they without errors */
            $entityManager->persist($pro);
            $entityManager->flush();

            /* Displaying success alert message */
            $this->addFlash('success', 'Gratki adminie, udało się dodać nowy projekt ;)');

            /* Rendering admin add page*/
            return $this->redirectToRoute('adminAdd');
        }

        /* If statement for first form that is valid and submitted */
        if($picForm->isSubmitted() && $picForm->isValid()){
            /* Variable containing all the data from form */
            $picFormData = $picForm->getData();

            /* Creating an array of all files name */
            $picArray = $picFormData['pictures'];

            $picProId = $picFormData['project']->getId();

            $proPic = $porPicRepository->findBy(['portfolio' => $picProId]);

            $backup = 0;
            $index = 1;

            foreach($proPic as $order){
                $backup++;
                $picOrder = $order->getPictureOrder();
                if($picOrder == $backup){
                    $index = $picOrder + 1;
                }
            }



/*            dd($high);*/
            /* Foreach loop executing process of changing file name, adding it to public/images folder and inserting all data to entity */
            foreach($picArray as $pic){


                /* Getting original name of the file */
                $originalName = pathinfo($pic->getClientOriginalName(), PATHINFO_FILENAME);
                /* Creating a safe name with slugger */
                $safeName = $slugger->slug($originalName);
                /* Creating new file name with unique id to keep it safe */
                $newName = $safeName.'-'.uniqid().'.'.$pic->guessExtension();

                /* Trying to move new file into directory if there will be an error then is sending an alert message */
                try{
                    $pic->move(
                        $this->getParameter('images'),
                        $newName
                    );
                } catch (FileException $e){
                    $this->addFlash('danger', $e);
                }

                /* Creating new row/s into picture portfolio repository */
                $picture = new PorPic(
                    $newName,
                    $index,
                    $picFormData['project']
                );

                /* Persist tells to doctrine to "manage" object, but to create query is flush responsibility because this method looking through all object to be persisted to database/entity if they without errors */
                $entityManager->persist($picture);
                $entityManager->flush();

                /* Incrementing order */
                $index += 1;
            }

            /* Displaying success alert message */
            $this->addFlash('success', 'Gratki adminie, udało się dodać nowe pictures ;)');

            /* Rendering admin add page*/
            return $this->redirectToRoute('adminAdd');
        }

        /* If statement for first form that is valid and submitted */
        if($skillForm->isSubmitted() && $skillForm->isValid()){
            /* Variable containing all the data from form */
            $skillFormData = $skillForm->getData();

            /* Creating new row in skills entity */
            $skill = new Skills(
                $skillFormData['category'],
                $skillFormData['name'],
                $skillFormData['percentage']
            );

            /* Persist tells to doctrine to "manage" object, but to create query is flush responsibility because this method looking through all object to be persisted to database/entity if they without errors */
            $entityManager->persist($skill);
            $entityManager->flush();

            /* Displaying success alert message */
            $this->addFlash('success', 'Gratki adminie, udało się dodać nową umiejętność ;)');

            /* Rendering admin add page*/
            return $this->redirectToRoute('adminAdd');
        }

        /* If statement for first form that is valid and submitted */
        if($expForm->isSubmitted() && $expForm->isValid()){
            /* Variable containing all the data from form */
            $expFormData = $expForm->getData();

            /* Creating new row in exp entity */
            $exp = new Exp(
                $expFormData['category'],
                $expFormData['title'],
                $expFormData['desc'],
                $expFormData['date']
            );

            /* Persist tells to doctrine to "manage" object, but to create query is flush responsibility because this method looking through all object to be persisted to database/entity if they without errors */
            $entityManager->persist($exp);
            $entityManager->flush();

            /* Displaying success alert message */
            $this->addFlash('success', 'Gratki adminie, udało się dodać nowe doświadczenie ;)');

            /* Rendering admin add page*/
            return $this->redirectToRoute('adminAdd');
        }

        /* Rendering admin add page*/
        return $this->renderForm('admin/adminAdd.html.twig', [
            'proForm' => $proForm,
            'picForm' => $picForm,
            'skillForm' => $skillForm,
            'expForm' => $expForm
        ]);
    }
}