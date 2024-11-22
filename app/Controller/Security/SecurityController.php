<?php

namespace App\Controller\Security;

use App\Core\Controller;
use App\Core\Form;
use App\Core\Route;
use App\Form\LoginForm;
use App\Form\UserForm;
use App\Model\User;

class SecurityController extends Controller
{
    #[Route('app.security.register', '/register', ['GET', 'POST'])]
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Form::validate($_POST, ['firstName', 'lastName', 'email', 'password'])) {
            $user = (new User)
                ->hydrate($_POST);

            if (!(new User)->findOneByEmail($user->getEmail())) {
                $user->setPassword(
                    password_hash($_POST['password'], PASSWORD_ARGON2I)
                )
                ->create();

            $this
                ->addFlash('success', 'Votre compte a bien été créé !')
                ->redirect('/login');
            } else {
                $this->addFlash('danger', 'Cet email existe déjà');
            }  
        }

        $form = new UserForm('/register');

        $this->render('Security/register.php', [
            'meta' => [
                'title' => 'Inscription'
            ],
            'form' => $form->create(),
        ]);
    }

    #[Route('app.security.login', '/login', ['GET', 'POST'])]
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Form::validate($_POST, ['email', 'password'])) {
            $user = (new User)->findOneByEmail(htmlspecialchars($_POST['email']));

            if ($user && password_verify($_POST['password'], $user->getPassword())) {
                $user->login();

                $this
                    ->addFlash('success', 'Vous êtes bien connecté à l\'appli')
                    ->redirect('/');
            } else {
                $this
                    ->addFlash('danger', 'Identifiants incorrects');
            }
        }

        $form = new LoginForm();

        $this->render('Security/login.php', [
            'meta' => [
                'title' => 'Connexion'
            ],
            'form' => $form->create(),
        ]);
    }

    #[Route('app.security.logout', '/logout', ['GET'] )]
    public function logout(): void
    {
        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }

        $this->redirect('/');
    }
}