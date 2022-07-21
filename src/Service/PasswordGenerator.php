<?php

namespace App\Service;

class PasswordGenerator
{
    public function generate(
        int  $length, bool $uppercaseLetters = false,
        bool $digits = false, bool $specialCharacters = false): string
    {

        $lowercaseLettersAlphabet = range('a', 'z');
        $uppercaseLettersAlphabet = range('A', 'Z');
        $digitsAlphabet = range(0, 9);

        $specialCharactersAlphabet = array_merge(
            range('!', '/'),
            range(':', '@'),
            range('[', '`'),
            range('{', '~'),
        );
        // Final alphabet defaults to all lowercase letters alphabet
        $finalAlphabet = [$lowercaseLettersAlphabet];

        // Start by adding a lowercase letter
        $password[] = $this->pickRandomItemFromAlphabet($lowercaseLettersAlphabet);

        // Map constraints to associated alphabets
        $mapping = [
            [$uppercaseLetters, $uppercaseLettersAlphabet],
            [$digits, $digitsAlphabet],
            [$specialCharacters, $specialCharactersAlphabet]
        ];

        // We make sure that the final password contains at least
        // one {uppercase letter and/or digit and/or special character}
        // based on user's requested constraints.
        // We also grow at the same time the final alphabet with
        // the alphabet of the requested constraint.
        foreach ($mapping as [$constraintEnabled, $constraintAlphabet]) {
            if ($constraintEnabled) {
                $finalAlphabet[] = $constraintAlphabet;

                // Add a random uppercase letter
                $password[] = $this->pickRandomItemFromAlphabet($constraintAlphabet);
            }
        }
        $finalAlphabet = array_merge(...$finalAlphabet);

        $numberOfCharactersRemaining = $length - count($password);

        for ($i = 0; $i < $numberOfCharactersRemaining; $i++) {
            $password[] = $this->pickRandomItemFromAlphabet($finalAlphabet);
        }

        // We shuffle the array to make the password characters order unpredictable
        $password = $this->secureShuffle($password);

        return implode('', $password);

    }

    private function pickRandomItemFromAlphabet(array $alphabet): string
    {

        return $alphabet[random_int(0, count($alphabet) - 1)];
    }

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
}