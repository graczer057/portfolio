<?php

namespace App\Controller\landing;

use App\Repository\PorPicRepository;
use App\Repository\PortfolioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class portfolioController extends AbstractController
{

    /* Function is only rendering a page with data from database*/
     #[Route('/portfolio', name:'portfolio')]
    public function portfolio(
        PortfolioRepository $portfolioRepository,
        PorPicRepository $porPicRepository
     ){
        /* Variables containing all portfolio and pictures */
        $pros = $portfolioRepository->findBy([], ['mainTitle' => 'ASC']);
        $pics = $porPicRepository->findBy([], ['pictureOrder' => 'ASC']);

        /* Rendering portfolio page with twig variables */
        return $this->render('landing/portfolio.html.twig', [
            'pros' => $pros,
            'pics' => $pics
        ]);
    }
}