<?php

namespace App\Controller\landing;

use App\Entity\Contact;
use App\Forms\contactType;
use App\Repository\AboutRepository;
use App\Repository\ExpRepository;
use App\Repository\MainRepository;
use App\Repository\PorPicRepository;
use App\Repository\PortfolioRepository;
use App\Repository\SkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class landingController extends AbstractController
{

    /* Function responsible for render a landing page, also including contact form handling */
     #[Route('/', name:'landingPage')]
    public function landing(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        MainRepository $mainRepository,
        AboutRepository $aboutRepository,
        PortfolioRepository $portfolioRepository,
        PorPicRepository $porPicRepository,
        SkillsRepository $skillsRepository,
        ExpRepository $expRepository
     ){
        /* Creating variables which contains every row from their entity */
        $main = $mainRepository->findAll();

        $about = $aboutRepository->findAll();

        $pros = $portfolioRepository->findBy([], ['mainTitle' => 'ASC']);

        $pics = $porPicRepository->findBy([], ['pictureOrder' => 'ASC']);

        $skills = $skillsRepository->findBy([], ['percentage' => 'DESC']);

        $exps = $expRepository->findBy([], ['date' => 'DESC']);

        /* Foreach loop segregating all the rows from experience entity by each category and assigning them into new variables to use in twig file */
        foreach($exps as $exp){

            if($exp->getCategory() == 1 ){
                $exp1[] = $exp;
            }else if($exp->getCategory() == 2){
                $exp2[] = $exp;
            }else if($exp->getCategory() == 3){
                $exp3[] = $exp;
            }else{
                $exp4[] = $exp;
            }

        }

        /* Creating variable with my email for later */
        $email = 'bartlomiej.szyszkowski.worker@gmail.com';

        /* Creating form and handle request to the database */
        $form = $this->createForm(contactType::class);
        $form->handleRequest($request);

        /* Condition to save form data (statement: form is submitted and valid, it means no errors or empty fields */
        if($form->isSubmitted() && $form->isValid()){
            /* Get all the data from form */
            $formData = $form->getData();

            /* Variable responsible for creating new contact and filling the data into new row in the entity */
            $contact = new Contact(
                $formData['subject']
            );

            /* Persist tells to doctrine to "manage" object, but to create query is flush responsibility because this method looking through all object to be persisted to database/entity if they without errors */
            $entityManager->persist($contact);
            $entityManager->flush();

            /* Creating array of subject and name to put that information into subject for the email I will receive */
            $array = [$formData['subject'], $formData['name']];

            /* Variable imploding array to get cleaner look of subject */
            $subject = implode(' - ', $array);

            /* Creating variable with new object (email) and filling a data from form in it */
            $message = (new Email())
                ->from($formData['email'])
                ->to($email)
                ->subject($subject)
                ->text($formData['description']);

            /* Sending an email from recruiter or employer strictly to me and notifying sender about success with flash message */
            $mailer->send($message);

            $this->addFlash('success', 'Gratulacje, twoja wiadomość została wysłana!');

            /* Last part on success is to redirect user to landing page */
            return $this->redirectToRoute('landingPage');
        }

        //By default, on landing rendering contact form and all data from database
        return $this->renderForm('landing/landing.html.twig', [
            'form' => $form,
            'mains' => $main ?? null,
            'abouts' => $about ?? null,
            'pros' => $pros ?? null,
            'pics' => $pics ?? null,
            'skills' => $skills ?? null,
            'exp1' => $exp1 ?? null,
            'exp2' => $exp2 ?? null,
            'exp3' => $exp3 ?? null,
            'exp4' => $exp4 ?? null
        ]);
    }
}