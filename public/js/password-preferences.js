const form = document.getElementById('js-password-generator-form');

const lengthSelect = document.getElementById('length');
const uppercaseLettersCheckbox = document.getElementById('uppercase-letters');
const digitsCheckbox = document.getElementById('digits');
const specialCharactersCheckbox = document.getElementById('special-characters');

const passwordPreferences = JSON.parse(localStorage.getItem('password_preferences'));

if (passwordPreferences) {
    lengthSelect.value = passwordPreferences.length;
    uppercaseLettersCheckbox.checked = passwordPreferences.uppercase_letters;
    digitsCheckbox.checked = passwordPreferences.digits;
    specialCharactersCheckbox.checked = passwordPreferences.special_characters;
}

form.addEventListener('submit', () => {
    localStorage.setItem('password_preferences', JSON.stringify({

        length: parseInt(lengthSelect.value, 10),
        uppercase_letters: uppercaseLettersCheckbox.checked,
        digits: digitsCheckbox.checked,
        special_characters: specialCharactersCheckbox.checked
    }));
});