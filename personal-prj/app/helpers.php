<?php

use App\Models\User;

if (!function_exists('current_user')) {

    /**
     * @return User|null
     */
    function current_user(): ?User
    {
        return Auth::user();
    }

//    /**
//     * @return \Modules\Account\src\Models\Account
//     */
//    function current_account(): ?Account
//    {
//        if (Auth::user() && Auth::user()->account_id != Auth::user()->account->id) {
//            Auth::user()->refresh();
//        }
//
//        return Auth::user() ? Auth::user()->account : null;
//    }
//}

    if (!function_exists('random_password')) {
        /**
         * @param int $length
         * @return string
         */
        function random_password(int $length = 8): string
        {
            $password = chr(rand(65, 90)) . chr(rand(97, 122)) . rand(0, 9) . Str::random($length - 3);

            return str_shuffle($password);
        }
    }

    if (!function_exists('pad_emojis')) {
        function pad_emojis($string)
        {
            $defaultEncoding = mb_regex_encoding();
            mb_regex_encoding("UTF-8");
            $string = mb_ereg_replace('([^\p{L}\s])', ' \\1 ', $string);
            mb_regex_encoding($defaultEncoding);
            return $string;
        }
    }
    if (!function_exists('set_csv_input_encoding')) {
        function set_csv_input_encoding($fileContent)
        {
            $enc = mb_detect_encoding($fileContent, mb_list_encodings(), true);

            if ($enc == 'SJIS') {
                Config::set('excel.imports.csv.input_encoding', 'SJIS-win');
            } else {
                Config::set('excel.imports.csv.input_encoding', 'UTF-8');
            }
        }
    }

    if (!function_exists('get_csv_input_encoding')) {
        function get_csv_input_encoding($path)
        {
            $fileContent = file_get_contents($path);

            return mb_detect_encoding($fileContent, mb_list_encodings(), true);
        }
    }
}
