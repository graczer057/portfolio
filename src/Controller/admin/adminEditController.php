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
     #[Route('edit', name: 'adminEdit')]
    public function adminEdit(MainRepository $mainRepository, AboutRepository $aboutRepository, PortfolioRepository $portfolioRepository, PorPicRepository $porPicRepository, SkillsRepository $skillsRepository, ExpRepository $expRepository){
        $main = $mainRepository->findAll();
        $about = $aboutRepository->findAll();
        $pors = $portfolioRepository->findAll();
        $pics = $porPicRepository->findAll();
        $skills = $skillsRepository->findAll();
        $exps = $expRepository->findAll();

        return $this->render('admin/adminEdit.html.twig', [
            'mains' => $main,
            'abouts' => $about,
            'pors' => $pors,
            'pics' => $pics,
            'skills' => $skills,
            'exps' => $exps
        ]);

    }

    /**
     * @Route("/edit/{id}", name="mainEdit")
     */
    public function mainEdit(Request $request, int $id, Main $main, MainRepository $mainRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger){
        $desc = $mainRepository->findOneBy(['id' => $id]);

        $mainForm = $this->createForm(mainEditType::class);
        $mainForm->handleRequest($request);

        if($mainForm->isSubmitted() && $mainForm->isValid()){
            $mainFormData = $mainForm->getData();

            if($mainFormData['name'] != null){
                $name = $desc->setName($mainFormData['name']);
                $entityManager->persist($name);
                $entityManager->flush();
                $nameInfo = "imię i nazwisko";
            }else{
                $nameInfo ="";
            }

            if($mainFormData['title'] != null){
                $title = $desc->setTitle($mainFormData['title']);
                $entityManager->persist($title);
                $entityManager->flush();
                $titleInfo = ", stanowisko";
            }else{
                $titleInfo = " ";
            }

            $picture = $mainFormData['picture'];

            if($picture != null){
                $fileSystem = new Filesystem();

                $fileSystem->remove('images/'.$desc->getPicture());

                $originalName = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = $slugger->slug($originalName);
                $newName = $safeName.'-'.uniqid().'.'.$picture->guessExtension();

                try{
                    $picture->move(
                        $this->getParameter('images'),
                        $newName
                    );
                } catch (FileException $e){
                    $this->addFlash('danger', $e);
                }
                $pic = $desc->setPicture($newName);
                $entityManager->persist($pic);
                $entityManager->flush();
                $pictureInfo = ", zdjęcie";
            }else{
                $pictureInfo=" ";
            }

            $this->addFlash('success', 'Udało się zmienić: '.$nameInfo.$titleInfo.$pictureInfo);
            return $this->redirectToRoute('adminEdit');
        }
        return $this->renderForm('admin/mainEdit.html.twig', [
            'mainForm' => $mainForm
        ]);
    }

    /**
     * @Route("/edit/about/{id}", name="aboutEdit")
     */
    public function aboutEdit(Request $request, int $id, About $about, AboutRepository $aboutRepository, EntityManagerInterface $entityManager){
        $desc = $aboutRepository->findOneBy(['id' => $id]);

        $aboutForm = $this->createForm(aboutEditType::class);
        $aboutForm->handleRequest($request);

        if($aboutForm->isSubmitted() && $aboutForm->isValid()){
            $aboutFormData = $aboutForm->getData();

            if($aboutFormData['desc'] != null){
                $data = $desc->setDescription($aboutFormData['desc']);

                $entityManager->persist($data);
                $entityManager->flush();
                $descInfo = "Opis";
            }else{
                $descInfo ="nic";
            }

            $this->addFlash('success', 'Udało się zmienić: '.$descInfo);
            return $this->redirectToRoute('adminEdit');
        }
        return $this->renderForm('admin/aboutEdit.html.twig', [
            'aboutForm' => $aboutForm
        ]);
    }

    /**
     * @Route("/edit/skill/{id}", name="skillEdit")
     */
    public function skillEdit(Request $request, int $id, Skills $skill, SkillsRepository $skillsRepository, EntityManagerInterface $entityManager){
        $desc = $skillsRepository->findOneBy(['id' => $id]);

        $skillForm = $this->createForm(skillsEditType::class);
        $skillForm->handleRequest($request);

        if($skillForm->isSubmitted() && $skillForm->isValid()){
            $skillFormData = $skillForm->getData();

            if($skillFormData['category'] != null){
                $cat = $desc->setCategory($skillFormData['category']);
                $entityManager->persist($cat);
                $entityManager->flush();
                $catInfo = "kategoria";
            }else{
                $catInfo = " ";
            }

            if($skillFormData['name'] != null){
                $name = $desc->setName($skillFormData['name']);
                $entityManager->persist($name);
                $entityManager->flush();
                $nameInfo = "nazwa";
            }else{
                $nameInfo =" ";
            }

            if($skillFormData['percentage'] != null){
                $percentage = $desc->setPercentage($skillFormData['percentage']);
                $entityManager->persist($percentage);
                $entityManager->flush();
                $perInfo = ", procenty";
            }else{
                $perInfo =" ";
            }

            $this->addFlash('success', 'Udało się zmienić: '.$catInfo.$nameInfo.$perInfo);
            return $this->redirectToRoute('adminEdit');
        }
        return $this->renderForm('admin/skillEdit.html.twig', [
            'skillForm' => $skillForm
        ]);
    }

    /**
     * @Route("/edit/por/{id}", name="porEdit")
     */
    public function porEdit(Request $request, int $id, portfolioRepository $porRepository, EntityManagerInterface $entityManager){
        $desc = $porRepository->findOneBy(['id' => $id]);

        $porForm = $this->createForm(porEditType::class);
        $porForm->handleRequest($request);

        if($porForm->isSubmitted() && $porForm->isValid()){
            $porFormData = $porForm->getData();

            if($porFormData['mainTitle'] != null){
                $mTitle = $desc->setMainTitle($porFormData['mainTitle']);
                $entityManager->persist($mTitle);
                $entityManager->flush();
                $mTitleInfo = " Tytul ";
            }else{
                $mTitleInfo = " ";
            }

            if($porFormData['worldLink'] != null){
                $wLink = $desc->setWorldLink($porFormData['worldLink']);
                $entityManager->persist($wLink);
                $entityManager->flush();
                $wLinkInfo = " link do strony ";
            }else{
                $wLinkInfo =" ";
            }

            if($porFormData['githubLink'] != null){
                $gLink = $desc->setGithubLink($porFormData['githubLink']);
                $entityManager->persist($gLink);
                $entityManager->flush();
                $gLinkInfo = " link do GH ";
            }else{
                $gLinkInfo =" ";
            }

            if($porFormData['description'] != null){
                $description = $desc->setDescription($porFormData['description']);
                $entityManager->persist($description);
                $entityManager->flush();
                $descInfo = " opis ";
            }else{
                $descInfo =" ";
            }

            if($porFormData['stack'] != null){
                $stack = $desc->setStack($porFormData['stack']);
                $entityManager->persist($stack);
                $entityManager->flush();
                $stackInfo = " stack ";
            }else{
                $stackInfo =" ";
            }

            $this->addFlash('success', 'Udało się zmienić: '.$mTitleInfo.$wLinkInfo.$gLinkInfo.$descInfo.$stackInfo);
            return $this->redirectToRoute('adminEdit');
        }
        return $this->renderForm('admin/porEdit.html.twig', [
            'porForm' => $porForm
        ]);
    }

    /**
     * @Route("/edit/order/up/{id}", name="orderUp")
     */
    public function orderUp(int $id, PorPicRepository $porPicRepository, EntityManagerInterface $entityManager){
        $newPic = $porPicRepository->findOneBy(['id' => $id]);

        $newPicOrder = $newPic->getPictureOrder();

        if($newPicOrder != 1){
            $oldPic = $porPicRepository->findOneBy(['pictureOrder' => $newPicOrder - 1]);

            $oldPicOrder = $oldPic->getPictureOrder();

            $newPicOrder -= 1;

            $oldPicOrder += 1;

            $new = $newPic->setPictureOrder($newPicOrder);

            $old = $oldPic->setPictureOrder($oldPicOrder);

            $entityManager->persist($new);
            $entityManager->persist($old);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adminEdit');
    }

    /**
     * @Route("/edit/order/down/{id}", name="orderDown")
     */
    public function orderDown(int $id, PorPicRepository $porPicRepository, EntityManagerInterface $entityManager){
        $newPic = $porPicRepository->findOneBy(['id' => $id]);

        $newPicOrder = $newPic->getPictureOrder();

        $oldPic = $porPicRepository->findOneBy(['pictureOrder' => $newPicOrder + 1]);

        $oldPicOrder = $oldPic->getPictureOrder();

        $newPicOrder += 1;

        $oldPicOrder -= 1;

        $new = $newPic->setPictureOrder($newPicOrder);

        $old = $oldPic->setPictureOrder($oldPicOrder);

        $entityManager->persist($new);
        $entityManager->persist($old);
        $entityManager->flush();

        return $this->redirectToRoute('adminEdit');
    }

    /**
     * @Route("/edit/exp/{id}", name="expEdit")
     */
    public function expEdit(Request $request, int $id, expRepository $expRepository, EntityManagerInterface $entityManager){
        $desc = $expRepository->findOneBy(['id' => $id]);

        $expForm = $this->createForm(expEditType::class);
        $expForm->handleRequest($request);

        if($expForm->isSubmitted() && $expForm->isValid()){
            $expFormData = $expForm->getData();

            if($expFormData['category'] != null){
                $cat = $desc->setCategory($expFormData['category']);
                $entityManager->persist($cat);
                $entityManager->flush();
                $catInfo = "kategoria";
            }else{
                $catInfo = " ";
            }

            if($expFormData['title'] != null){
                $title = $desc->setTitle($expFormData['title']);
                $entityManager->persist($title);
                $entityManager->flush();
                $titleInfo = "tytul";
            }else{
                $titleInfo =" ";
            }

            if($expFormData['desc'] != null){
                $description = $desc->setDescription($expFormData['desc']);
                $entityManager->persist($description);
                $entityManager->flush();
                $descInfo = ", opis";
            }else{
                $descInfo =" ";
            }

            if($expFormData['date'] != null){
                $date = $desc->setDate($expFormData['date']);
                $entityManager->persist($date);
                $entityManager->flush();
                $dateInfo = ", data";
            }else{
                $dateInfo =" ";
            }

            $this->addFlash('success', 'Udało się zmienić: '.$catInfo.$titleInfo.$descInfo.$dateInfo);
            return $this->redirectToRoute('adminEdit');
        }
        return $this->renderForm('admin/expEdit.html.twig', [
            'expForm' => $expForm
        ]);
    }
}