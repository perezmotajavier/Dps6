<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;

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
        // We make sure that the password length is always
        // at minimum {app.password_min_length}
        // and at maximum {app.password_max_length}.
        $length = max(
            min($request->query->getInt('length'), $this->getParameter('app.password_max_length')),
            $this->getParameter('app.password_min_length')
        );
        $UppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $digits = $request->query->getBoolean('digits');
        $SpecialCharacters = $request->query->getBoolean('special_characters');


        $password = $passwordGenerator->generate(
            $length,
            $UppercaseLetters,
            $digits,
            $SpecialCharacters
        );


        $response = $this->render('pages/password.html.twig', compact('password'));

        $response->headers->setCookie(cookie: Cookie::create('app_length', $length, new DateTimeImmutable('+5 years')));

        $response->headers->setCookie(Cookie::create('app_uppercase_letters', $UppercaseLetters ? '1' : '0', new DateTimeImmutable('+5 years')));

        $response->headers->setCookie(Cookie::create('app_digits', $digits ? '1' : '0', new DateTimeImmutable('+5 years')));

        $response->headers->setCookie(Cookie::create('app_special_characters', $SpecialCharacters ? '1' : '0', new DateTimeImmutable('+5 years')));

        return $response;
    }
}
