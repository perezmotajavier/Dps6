<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {

        return $this->render('pages/home.html.twig');
    }

    /**
     *
     */
    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request)
    {
        dump($request->query->all());
        return $this->render('pages/password.html.twig');

    }
}
