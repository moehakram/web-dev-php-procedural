<?php

if (is_user_logged_in()) {
    redirect_to('index.php');
}

$inputs = [];
$errors = [];

if (is_post_request()) {

    [$inputs, $errors] = filter($_POST, [
        'username' => 'string | required',
        'password' => 'string | required',
        'remember_me' => 'string'
    ],[
        'required' => '%s wajib diisi'
    ]);

    if ($errors) {
        redirect_with('login.php', ['errors' => $errors, 'inputs' => $inputs]);
    }

    if (!login($inputs['username'], $inputs['password'], isset($inputs['remember_me']))) {

        $errors['login'] = 'Invalid username or password';

        redirect_with('login.php', [
            'errors' => $errors,
            'inputs' => $inputs
        ]);
    }

    redirect_to('index.php');

} else if (is_get_request()) {
    [$errors, $inputs] = session_flash('errors', 'inputs');
}