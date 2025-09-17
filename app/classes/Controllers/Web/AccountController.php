<?php namespace MusicCollection\Controllers\Web;

use MusicCollection\Controllers\BaseController;
use MusicCollection\Databases\Models\User;
use MusicCollection\Responses\View;
use MusicCollection\Translation\Translator as T;
use MusicCollection\Utils\Logger;
use MusicCollection\Validation\LoginValidator;

/**
 * Class AccountController
 * @package MusicCollection\Controllers\Web
 * @noinspection PhpUnused
 */
class AccountController extends BaseController
{
    protected function initialize(): void
    {
        if ($this->session->get('errors')) {
            $this->errors = array_merge($this->session->get('errors'), $this->errors);
        }

        $this->session->delete('errors');
    }

    /**
     * @return View
     * @noinspection PhpUnused
     */
    protected function login(): View
    {
        //If already logged in, no need to be here
        if ($this->session->keyExists('user')) {
            header('Location: ' . BASE_PATH);
            exit;
        }

        $email = $this->session->get('email');
        $this->session->delete('email');

        //Return formatted data
        return $this->view->render('account.login', [
            'pageTitle' => T::__('account.login.pageTitle'),
            'email' => $email,
            'location' => $this->request->query('location', ''),
            'errors' => $this->errors
        ]);
    }

    /**
     * @noinspection PhpUnused
     */
    protected function loginPost(): never
    {
        //Redirect any false entries
        if (!$this->request->hasInput('submit')) {
            header('Location: ' . BASE_PATH);
            exit;
        }

        //Set post variables
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $location = $this->request->input('location');

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
        $this->errors = $validator->errors;

        //When no error, set session variable, redirect & exit script
        if (empty($this->errors)) {
            $this->session->set('user', $user);
            header('Location: ' . $location);
            exit;
        }

        //Whoops, we have errors and need to return
        $this->session->set('email', $email);
        $this->session->set('errors', $this->errors);
        header('Location: ' . $this->request->previousPath());
        exit;
    }

    /**
     * @noinspection PhpUnused
     */
    protected function logout(): never
    {
        $this->session->destroy();
        header('Location: ' . BASE_PATH);
        exit;
    }
}
