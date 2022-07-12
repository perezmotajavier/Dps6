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

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request): Response
    {
        $length = $request->query->getInt('lenght');

        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');

        $digits = $request->query->getBoolean('digits');

        $specialCharacters = $request->query->getBoolean('special_characters');

        $lowercaseLettersAlphabet = range('a', 'z');

        $uppercaseLettersAlphabet = range('A', 'Z');

        $digitsAlphabet = range(0, 9);

        $specialCharactersAlphabet = ['!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~'];

        $finalAlphabet = $lowercaseLettersAlphabet;

        $password = '';

        // We add a lowercase letter randomly
        $password .= $this->pickRandomItemFromAlphabet($lowercaseLettersAlphabet);

        if ($uppercaseLetters) {
            $finalAlphabet = array_merge($finalAlphabet, $uppercaseLettersAlphabet);

            // Add a random uppercase letter
            $password .= $this->pickRandomItemFromAlphabet($uppercaseLettersAlphabet);
        }

        if ($digits) {
            $finalAlphabet = array_merge($finalAlphabet, $digitsAlphabet);

            // Add a random number
            $password .= $this->pickRandomItemFromAlphabet($digitsAlphabet);

        }

        if ($specialCharacters) {
            $finalAlphabet = array_merge($finalAlphabet, $specialCharactersAlphabet);

            // Add special characters randomly
            $password .= $this->pickRandomItemFromAlphabet($specialCharactersAlphabet);
        }

        $numberOfCharactersRemaining = $length - mb_strlen($password);

        for ($i = 0; $i < $numberOfCharactersRemaining; $i++) {
            $password .= $this->pickRandomItemFromAlphabet($finalAlphabet);
        }

        $password = str_split($password);

        $password = $this->secureShuffle($password);

        $password = implode('', $password);


        return $this->render('pages/password.html.twig', compact('password'));

    }

    /**
     * @param array $arr
     * @return array
     * @throws \Exception
     */
    private function secureShuffle(array $arr): array
    {
        // Source: https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
        $length = count($arr);

        for ($i = $length - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
        return $arr;
    }

    private function pickRandomItemFromAlphabet(array $alphabet): string
    {
        return $alphabet[random_int(0, count($alphabet) - 1)];
    }
}
