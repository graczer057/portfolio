<?php

namespace App\Controller\admin;

use App\Entity\About;
use App\Entity\Main;
use App\Entity\Skills;
use App\Forms\adminPanel\aboutEditType;
use App\Forms\adminPanel\expEditType;
use App\Forms\adminPanel\mainEditType;
use App\Forms\adminPanel\porEditType;
use App\Forms\adminPanel\skillsEditType;
use App\Repository\AboutRepository;
use App\Repository\ExpRepository;
use App\Repository\MainRepository;
use App\Repository\PorPicRepository;
use App\Repository\PortfolioRepository;
use App\Repository\SkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
class adminEditController extends AbstractController
{
    /* Function responsible for rendering main edit template with all data from database */
     #[Route('/admin/edit', name: 'adminEdit')]
    public function adminEdit(MainRepository $mainRepository, AboutRepository $aboutRepository, PortfolioRepository $portfolioRepository, PorPicRepository $porPicRepository, SkillsRepository $skillsRepository, ExpRepository $expRepository){

         /* Creating variables with all data */
         $main = $mainRepository->findAll();
         $about = $aboutRepository->findAll();
         $pros = $portfolioRepository->findAll();
         $pics = $porPicRepository->findBy([], ['pictureOrder' => 'ASC']);
         $skills = $skillsRepository->findAll();
         $exps = $expRepository->findAll();

         /* Rendering main template with data */
         return $this->render('admin/adminEdit.html.twig', [
             'mains' => $main,
             'abouts' => $about,
             'pros' => $pros,
             'pics' => $pics,
             'skills' => $skills,
             'exps' => $exps
         ]);

    }

     /* Function responsible for editing main entity */
     #[Route('/admin/edit/main/{id}', name: 'mainEdit')]
     public function mainEdit(Request $request, int $id, Main $main, MainRepository $mainRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger){

         /* Finding one specific row */
         $desc = $mainRepository->findOneBy(['id' => $id]);

         /* Creating form and handle request */
         $mainForm = $this->createForm(mainEditType::class);
         $mainForm->handleRequest($request);

         /* Checking if form is submitted and valid */
         if($mainForm->isSubmitted() && $mainForm->isValid()){

             /* Getting data from form */
             $mainFormData = $mainForm->getData();

             /* If admin change name, then setting the data into row in entity, persisting and flushing, also creating variable with info that name was changed else only creating variable that name was not changed */
             if($mainFormData['name'] != null){
                $name = $desc->setName($mainFormData['name']);

                $entityManager->persist($name);
                $entityManager->flush();

                $nameInfo = "imię i nazwisko ";
                $notName = " ";

            }else{
                 $nameInfo = " ";
                 $notName = "imię i nazwisko ";
            }

             /* If admin change title, then setting the data into row in entity, persisting and flushing, also creating variable with info that title was changed else only creating variable that title was not changed */
            if($mainFormData['title'] != null){
                $title = $desc->setTitle($mainFormData['title']);

                $entityManager->persist($title);
                $entityManager->flush();

                $titleInfo = " stanowisko ";
                $notTitle = " ";
            }else{
                $titleInfo = " ";
                $notTitle = " stanowisko ";
            }

            /* Creating variable containing file name */
            $picture = $mainFormData['picture'];

             /* If admin change image, then setting the data into row in entity, persisting and flushing, also creating variable with info that image was changed else only creating variable that image was not changed */
             if($picture != null){

                 /* Creating new filesystem and removing previous picture */
                 $fileSystem = new Filesystem();
                 $fileSystem->remove('images/'.$desc->getPicture());

                 /* getting original file name into variable */
                 $originalName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);

                 /* Creating safe name of original file name using slugger */
                 $safeName = $slugger->slug($originalName);

                 /* Creating new file name with unique id */
                 $newName = $safeName.'-'.uniqid().'.'.$picture->guessExtension();

                 /* Trying to move new file into images directory if there will be error it will show an exception */
                 try{
                     $picture->move(

                         $this->getParameter('images'),

                         $newName
                     );
                 } catch (FileException $e){
                     $this->addFlash('danger', $e);
                 }

                 /* Setting new data into column picture */
                 $pic = $desc->setPicture($newName);

                 /* Persisting and flushing */
                 $entityManager->persist($pic);
                 $entityManager->flush();

                 /* Setting variable with info what changed */
                 $pictureInfo = " zdjęcie ";
                 $notPicture = " ";
             }else{
                 $pictureInfo=" ";
                 $notPicture = " zdjęcie ";
             }

             /* Adding success flash message with info what was changed and what was not changed */
             $this->addFlash('success', 'Udało się zmienić: '.$nameInfo.$titleInfo.$pictureInfo.'|   Nie zmieniło się: '.$notName.$notTitle.$notPicture);

             /* After all redirecting user (admin) to admin Edit route */
             return $this->redirectToRoute('adminEdit');
        }

         /* Rendering form */
         return $this->renderForm('admin/mainEdit.html.twig', [
             'mainForm' => $mainForm
         ]);
     }

    /* Function responsible for editing about entity */
    #[Route('/admin/edit/about/{id}', name: 'aboutEdit')]
    public function aboutEdit(Request $request, int $id, About $about, AboutRepository $aboutRepository, EntityManagerInterface $entityManager){

        /* Finding one specific row */
        $desc = $aboutRepository->findOneBy(['id' => $id]);

        /* Creating form and handle request */
        $aboutForm = $this->createForm(aboutEditType::class);
        $aboutForm->handleRequest($request);

        /* Checking if form is submitted and valid */
        if($aboutForm->isSubmitted() && $aboutForm->isValid()){

            /* Getting data from form */
            $aboutFormData = $aboutForm->getData();

            /* If admin change description, then setting the data into row in entity, persisting and flushing, also creating variable with info that description was changed else only creating variable that description was not changed */
            if($aboutFormData['desc'] != null){
                $data = $desc->setDescription($aboutFormData['desc']);

                $entityManager->persist($data);
                $entityManager->flush();
                $descInfo = " Opis ";
                $notDesc = " ";
            }else{
                $descInfo =" ";
                $notDesc = " Opis ";
            }

            /* Adding success flash message with info what was changed and what was not changed */
            $this->addFlash('success', 'Udało się zmienić: '.$descInfo.'|   Nie zmieniło się: '.$notDesc);

            /* After all redirecting user (admin) to admin Edit route */
            return $this->redirectToRoute('adminEdit');
        }

        /* Rendering form */
        return $this->renderForm('admin/aboutEdit.html.twig', [
            'aboutForm' => $aboutForm
        ]);
    }

    /* Function responsible for editing skill entity */
    #[Route('/admin/edit/skill/{id}', name: 'skillEdit')]
    public function skillEdit(Request $request, int $id, Skills $skill, SkillsRepository $skillsRepository, EntityManagerInterface $entityManager){

        /* Finding one specific row */
        $desc = $skillsRepository->findOneBy(['id' => $id]);

        /* Creating form and handle request */
        $skillForm = $this->createForm(skillsEditType::class);
        $skillForm->handleRequest($request);

        /* Checking if form is submitted and valid */
        if($skillForm->isSubmitted() && $skillForm->isValid()){

            /* Getting data from form */
            $skillFormData = $skillForm->getData();

            /* If admin change category, then setting the data into row in entity, persisting and flushing, also creating variable with info that category was changed else only creating variable that category was not changed */
            if($skillFormData['category'] != null){
                $cat = $desc->setCategory($skillFormData['category']);

                $entityManager->persist($cat);
                $entityManager->flush();

                $catInfo = " kategoria ";
                $notCat = " ";
            }else{
                $catInfo = " ";
                $notCat = " kategoria ";
            }

            /* If admin change name, then setting the data into row in entity, persisting and flushing, also creating variable with info that name was changed else only creating variable that name was not changed */
            if($skillFormData['name'] != null){
                $name = $desc->setName($skillFormData['name']);

                $entityManager->persist($name);
                $entityManager->flush();

                $nameInfo = " nazwa ";
                $notName = " ";
            }else{
                $nameInfo =" ";
                $notName = " nazwa ";
            }

            /* If admin change percentage, then setting the data into row in entity, persisting and flushing, also creating variable with info that percentage was changed else only creating variable that percentage was not changed */
            if($skillFormData['percentage'] != null){
                $percentage = $desc->setPercentage($skillFormData['percentage']);

                $entityManager->persist($percentage);
                $entityManager->flush();

                $perInfo = " procenty ";
                $notPer = " ";
            }else{
                $perInfo = " ";
                $notPer = " procenty ";
            }

            /* Adding success flash message with info what was changed and what was not changed */
            $this->addFlash('success', 'Udało się zmienić: '.$catInfo.$nameInfo.$perInfo.'|     Nie zmieniło się: '.$notCat.$notName.$notPer);

            /* After all redirecting user (admin) to admin Edit route */
            return $this->redirectToRoute('adminEdit');
        }

        /* Rendering form */
        return $this->renderForm('admin/skillEdit.html.twig', [
            'skillForm' => $skillForm
        ]);
    }

    /* Function responsible for editing portfolio entity */
    #[Route('/admin/edit/pro/{id}', name: 'proEdit')]
    public function proEdit(Request $request, int $id, portfolioRepository $proRepository, EntityManagerInterface $entityManager){

        /* Finding one specific row */
        $desc = $proRepository->findOneBy(['id' => $id]);

        /* Creating form and handle request */
        $proForm = $this->createForm(porEditType::class);
        $proForm->handleRequest($request);

        /* Checking if form is submitted and valid */
        if($proForm->isSubmitted() && $proForm->isValid()){

            /* Getting data from form */
            $proFormData = $proForm->getData();

            /* If admin change main title, then setting the data into row in entity, persisting and flushing, also creating variable with info that main title was changed else only creating variable that main title was not changed */
            if($proFormData['mainTitle'] != null){
                $mTitle = $desc->setMainTitle($proFormData['mainTitle']);

                $entityManager->persist($mTitle);
                $entityManager->flush();

                $mTitleInfo = " Tytuł ";
                $notTitle = " ";
            }else{
                $mTitleInfo = " ";
                $notTitle = " Tytuł ";
            }

            /* If admin change world link, then setting the data into row in entity, persisting and flushing, also creating variable with info that world link was changed else only creating variable that world link was not changed */
            if($proFormData['worldLink'] != null){
                $wLink = $desc->setWorldLink($proFormData['worldLink']);

                $entityManager->persist($wLink);
                $entityManager->flush();

                $wLinkInfo = " link do strony ";
                $notWorldLink = " ";
            }else{
                $wLinkInfo =" ";
                $notWorldLink = " link do strony ";
            }

            /* If admin change github link, then setting the data into row in entity, persisting and flushing, also creating variable with info that github link was changed else only creating variable that github link was not changed */
            if($proFormData['githubLink'] != null){
                $gLink = $desc->setGithubLink($proFormData['githubLink']);

                $entityManager->persist($gLink);
                $entityManager->flush();

                $gLinkInfo = " link do githuba ";
                $notGhLink = " ";
            }else{
                $gLinkInfo =" ";
                $notGhLink = " link do githuba ";
            }

            /* If admin change description, then setting the data into row in entity, persisting and flushing, also creating variable with info that description was changed else only creating variable that description was not changed */
            if($proFormData['description'] != null){
                $description = $desc->setDescription($proFormData['description']);

                $entityManager->persist($description);
                $entityManager->flush();

                $descInfo = " opis ";
                $notDesc = " ";
            }else{
                $descInfo =" ";
                $notDesc = " opis ";
            }

            /* If admin change stack, then setting the data into row in entity, persisting and flushing, also creating variable with info that stack was changed else only creating variable that stack was not changed */
            if($proFormData['stack'] != null){
                $stack = $desc->setStack($proFormData['stack']);

                $entityManager->persist($stack);
                $entityManager->flush();

                $stackInfo = " stack ";
                $notStack = " ";
            }else{
                $stackInfo =" ";
                $notStack = " stack ";
            }

            /* Adding success flash message with info what was changed and what was not changed */
            $this->addFlash('success', 'Udało się zmienić: '.$mTitleInfo.$wLinkInfo.$gLinkInfo.$descInfo.$stackInfo.'|    Nie zmieniło się: '.$notTitle.$notWorldLink.$notGhLink.$notDesc.$notStack);

            /* After all redirecting user (admin) to admin Edit route */
            return $this->redirectToRoute('adminEdit');
        }

        /* Rendering form */
        return $this->renderForm('admin/porEdit.html.twig', [
            'proForm' => $proForm
        ]);
    }

    /* Function responsible for editing picture order up */
    #[Route('/admin/edit/order/up/{id}', name: 'orderUp')]
    public function orderUp(int $id, PorPicRepository $porPicRepository, EntityManagerInterface $entityManager){

        /* Finding one specific row */
        $newPic = $porPicRepository->findOneBy(['id' => $id]);

        /* Getting single information (picture order) from array (whole data from the row) to the variable */
        $newPicOrder = $newPic->getPictureOrder();

        /* Checking that the picture order is not a 1 (without this statement picture order could be for new 1 and old 0) */
        if($newPicOrder != 1){

            /* Finding picture above by picture order less than one we have */
            $oldPic = $porPicRepository->findOneBy(['pictureOrder' => $newPicOrder - 1]);

            /* Getting single information from an array */
            $oldPicOrder = $oldPic->getPictureOrder();

            /* Setting new picture order for requested picture */
            $newPicOrder -= 1;

            /* Setting new picture order for picture from above requested */
            $oldPicOrder += 1;

            /* Setting into existing row in entity new picture order for requested picture*/
            $new = $newPic->setPictureOrder($newPicOrder);

            /* Setting into existing row in entity new picture order for picture from above requested */
            $old = $oldPic->setPictureOrder($oldPicOrder);

            /* Persisting two and flushing */
            $entityManager->persist($new);
            $entityManager->persist($old);
            $entityManager->flush();
        }

        /* After all redirecting user (admin) to admin Edit route */
        return $this->redirectToRoute('adminEdit');
    }

    /* Function responsible for editing picture order down */
    #[Route('/admin/edit/order/down/{id}', name: 'orderDown')]
    public function orderDown(int $id, PorPicRepository $porPicRepository, EntityManagerInterface $entityManager){

        /* Finding one specific row */
        $newPic = $porPicRepository->findOneBy(['id' => $id]);

        /* Getting single information (picture order) from array (whole data from the row) to the variable */
        $newPicOrder = $newPic->getPictureOrder();

        /* Finding picture bellow by picture order greater than one we have */
        $oldPic = $porPicRepository->findOneBy(['pictureOrder' => $newPicOrder + 1]);

        /* Getting single information from an array */
        $oldPicOrder = $oldPic->getPictureOrder();

        /* Setting new picture order for requested picture */
        $newPicOrder += 1;

        /* Setting new picture order for picture from bellow requested */
        $oldPicOrder -= 1;

        /* Setting into existing row in entity new picture order for requested picture*/
        $new = $newPic->setPictureOrder($newPicOrder);

        /* Setting into existing row in entity new picture order for picture from bellow requested */
        $old = $oldPic->setPictureOrder($oldPicOrder);

        /* Persisting two and flushing */
        $entityManager->persist($new);
        $entityManager->persist($old);
        $entityManager->flush();

        /* After all redirecting user (admin) to admin Edit route */
        return $this->redirectToRoute('adminEdit');
    }

    /* Function responsible for editing experience entity */
    #[Route('/admin/edit/exp/{id}', name: 'expEdit')]
    public function expEdit(Request $request, int $id, expRepository $expRepository, EntityManagerInterface $entityManager){

        /* Finding one specific row */
        $desc = $expRepository->findOneBy(['id' => $id]);

        /* Creating form and handle request */
        $expForm = $this->createForm(expEditType::class);
        $expForm->handleRequest($request);

        /* Checking if form is submitted and valid */
        if($expForm->isSubmitted() && $expForm->isValid()){

            /* Getting data from form */
            $expFormData = $expForm->getData();

            /* If admin change category, then setting the data into row in entity, persisting and flushing, also creating variable with info that category was changed else only creating variable that category was not changed */
            if($expFormData['category'] != null){
                $cat = $desc->setCategory($expFormData['category']);

                $entityManager->persist($cat);
                $entityManager->flush();

                $catInfo = " kategoria ";
                $notCat = " ";
            }else{
                $catInfo = " ";
                $notCat = " kategoria ";
            }

            /* If admin change title, then setting the data into row in entity, persisting and flushing, also creating variable with info that title was changed else only creating variable that title was not changed */
            if($expFormData['title'] != null){
                $title = $desc->setTitle($expFormData['title']);

                $entityManager->persist($title);
                $entityManager->flush();

                $titleInfo = " tytuł ";
                $notTitle = " ";
            }else{
                $titleInfo =" ";
                $notTitle = " tytuł ";
            }

            /* If admin change description, then setting the data into row in entity, persisting and flushing, also creating variable with info that description was changed else only creating variable that description was not changed */
            if($expFormData['desc'] != null){
                $description = $desc->setDescription($expFormData['desc']);

                $entityManager->persist($description);
                $entityManager->flush();

                $descInfo = " opis ";
                $notDesc = " ";
            }else{
                $descInfo =" ";
                $notDesc = " opis ";
            }

            /* If admin change date, then setting the data into row in entity, persisting and flushing, also creating variable with info that date was changed else only creating variable that date was not changed */
            if($expFormData['date'] != null){
                $date = $desc->setDate($expFormData['date']);

                $entityManager->persist($date);
                $entityManager->flush();

                $dateInfo = " data ";
                $notDate = " ";
            }else{
                $dateInfo =" ";
                $notDate = " data ";
            }

            /* Adding success flash message with info what was changed and what was not changed */
            $this->addFlash('success', 'Udało się zmienić: '.$catInfo.$titleInfo.$descInfo.$dateInfo.'|     Nie zmieniło się: '.$notCat.$notTitle.$notDesc.$notDate);

            /* After all redirecting user (admin) to admin Edit route */
            return $this->redirectToRoute('adminEdit');
        }

        /* Rendering form */
        return $this->renderForm('admin/expEdit.html.twig', [
            'expForm' => $expForm
        ]);
    }
}