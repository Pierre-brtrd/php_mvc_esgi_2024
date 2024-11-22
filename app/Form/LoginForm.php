<?php

namespace App\Form;

use App\Core\Form;

class LoginForm extends Form
{
    public function __construct()
    {
        $this
            ->startForm($_SERVER['REQUEST_URI'], 'POST', ['class' => 'form card p-4 mt-3'])
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('email', 'Email:', ['class' => 'form-label'])
            ->addInput('email', 'email', [
                'class' => 'form-control',
                'id' => 'email',
                'placeholder' => 'john@example.com',
                'required' => true
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('password', 'Mot de passe:', ['class' => 'form-label'])
            ->addInput('password', 'password', [
                'class' => 'form-control',
                'id' => 'password',
                'placeholder' => 'S3CR3T',
                'required' => true
            ])
            ->endDiv()
            ->addButton('Connexion', [
                'class' => 'btn btn-primary',
                'type' => 'submit'
            ])
            ->endForm()
        ;
    }
}