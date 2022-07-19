<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('pages/home.html.twig', [
            'password_default_length' => $this->getParameter('app.password_default_length'),
            'password_min_length' => $this->getParameter('app.password_min_length'),
            'password_max_length' => $this->getParameter('app.password_max_length')
        ]);
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        $length = max(
            min($request->query->getInt('length'), $this->getParameter('app.password_max_length')),
            $this->getParameter('app.password_min_length')
        );
        $password = $passwordGenerator->generate(
            $length,
            uppercaseLetters: $request->query->getBoolean('uppercase_letters'),
            digits: $request->query->getBoolean('digits'),
            specialCharacters: $request->query->getBoolean('special_characters'),
        );

        return $this->render('pages/password.html.twig', compact('password'));
    }
}
