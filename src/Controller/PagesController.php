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
    public function home(Request $request): Response

    {
        return $this->render(view: 'pages/home.html.twig', parameters: [

            'password_default_length' => $request->cookies->getInt(
                key: 'app_length', default: $this->getParameter(name: 'app.password_default_length')),

            'uppercase_letters' => $request->cookies->getBoolean(key: 'app_uppercase_letters'),

            'digits' => $request->cookies->getBoolean(key: 'app_digits'),

            'special_characters' => $request->cookies->getBoolean(key: 'app_special_characters'),

            'password_min_length' => $this->getParameter(name: 'app.password_min_length'),
            'password_max_length' => $this->getParameter(name: 'app.password_max_length')
        ]);
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        // We make sure that the password length is always
        // at minimum {app.password_min_length}
        // and at maximum {app.password_max_length}.
        $length = max(
            min($request->query->getInt(key: 'length'), $this->getParameter(name: 'app.password_max_length')),
            $this->getParameter(name: 'app.password_min_length')
        );
        $UppercaseLetters = $request->query->getBoolean(key: 'uppercase_letters');
        $digits = $request->query->getBoolean(key: 'digits');
        $SpecialCharacters = $request->query->getBoolean(key: 'special_characters');


        $password = $passwordGenerator->generate(
            $length,
            $UppercaseLetters,
            $digits,
            $SpecialCharacters
        );


        $response = $this->render(view: 'pages/password.html.twig', parameters: compact(var_name: 'password'));

        $response->headers->setCookie(cookie: Cookie::create(name: 'app_length', value: $length, expire: new DateTimeImmutable(datetime: '+5 years')));

        $response->headers->setCookie(cookie: Cookie::create(name: 'app_uppercase_letters', value: $UppercaseLetters ? '1' : '0', expire: new DateTimeImmutable(datetime: '+5 years')));

        $response->headers->setCookie(cookie: Cookie::create(name: 'app_digits', value: $digits ? '1' : '0', expire: new DateTimeImmutable(datetime: '+5 years')));

        $response->headers->setCookie(cookie: Cookie::create(name: 'app_special_characters', value: $SpecialCharacters ? '1' : '0', expire: new DateTimeImmutable(datetime: '+5 years')));

        return $response;
    }
}
