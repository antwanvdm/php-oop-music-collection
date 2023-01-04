<?php namespace MusicCollection\Handlers;

use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Logger;
use MusicCollection\Databases\Objects\User;
use MusicCollection\Form\Data;
use MusicCollection\Form\Validation\LoginValidator;

/**
 * Class AccountHandler
 * @package MusicCollection\Handlers
 * @noinspection PhpUnused
 */
class AccountHandler extends BaseHandler
{
    protected function initialize(): void
    {
        if ($this->session->get('errors')) {
            $this->errors = array_merge($this->session->get('errors'), $this->errors);
        }

        $this->session->delete('errors');
    }

    /**
     * @noinspection PhpUnused
     */
    protected function login(): void
    {
        //If already logged in, no need to be here
        if ($this->session->keyExists('user')) {
            header('Location: ' . BASE_PATH);
            exit;
        }

        //Return formatted data
        $this->renderTemplate([
            'pageTitle' => T::__('account.login.pageTitle'),
            'email' => $this->session->get('email'),
            'location' => $_GET['location'] ?? '',
            'errors' => $this->errors
        ]);

        $this->session->delete('email');
    }

    protected function loginPost(): void
    {
        //Redirect any false entries
        if (!isset($_POST['submit'])) {
            header('Location: ' . BASE_PATH);
            exit;
        }

        //Set form data
        $formData = new Data($_POST);

        //Set post variables
        $email = $formData->getPostVar('email');
        $password = $formData->getPostVar('password');
        $location = $formData->getPostVar('location');

        //Get the record from the db
        try {
            $user = User::getByEmail($email);
        } catch (\Exception $e) {
            //Probably should work nicer
            Logger::error($e);
            $user = new User();
        }

        //Actual validation
        $validator = new LoginValidator($user, $password);
        $validator->validate();
        $this->errors = $validator->getErrors();

        //When no error, set session variable, redirect & exit script
        if (empty($this->errors)) {
            $this->session->set('user', $user);
            header('Location: ' . $location);
            exit;
        }

        //Whoops, we have errors and need to return
        $this->session->set('email', $email);
        $this->session->set('errors', $this->errors);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * @noinspection PhpUnused
     */
    protected function logout(): void
    {
        $this->session->destroy();
        header('Location: ' . BASE_PATH);
        exit;
    }

    protected function register(): void
    {
        //TEMP script just to add an user.
        $user = new User();
        $user->email = 'moora@hr.nl';
        $user->password = password_hash('test', PASSWORD_ARGON2I);
        $user->name = 'Antwan';
        $user->save();
        exit;

//        $user = User::getByEmail('moora@hr.nl');
//        $user->name = "Antwann";
//        $user->save();
//        exit;
    }
}
