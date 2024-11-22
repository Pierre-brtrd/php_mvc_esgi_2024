<?php

namespace App\Form;

use App\Core\Form;
use App\Model\User;

class UserForm extends Form
{
    public function __construct(string $action, ?User $user = null)
    {
        $this
            ->startForm($action, 'POST', ['class' => 'card p-4 mt-3'])
            ->startDiv(['class' => 'row mb-3'])
            ->startDiv(['class' => 'col-md-6'])
            ->addLabel('firstName', 'Prénom:', ['class' => 'form-label'])
            ->addInput('text', 'firstName', [
                'class' => 'form-control',
                'id' => 'firstName',
                'required' => true,
                'placeholder' => 'John',
                'value' => $user ?->getFirstName(),
            ])
            ->endDiv()
            ->startDiv(['class' => 'col-md-6'])
            ->addLabel('lastName', 'Nom:', ['class' => 'form-label'])
            ->addInput('text', 'lastName', [
                'class' => 'form-control',
                'id' => 'lastName',
                'required' => true,
                'placeholder' => 'Doe',
                'value' => $user ?->getLastName(),

            ])
            ->endDiv()
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('email', 'Email:', ['class' => 'form-label'])
            ->addInput('email', 'email', [
                'class' => 'form-control',
                'id' => 'email',
                'required' => true,
                'placeholder' => 'johndoe@example',
                'value' => $user ?->getEmail(),
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('password', 'Mot de passe:', ['class' => 'form-label'])
            ->addInput('password', 'password', [
                'class' => 'form-control',
                'id' => 'password',
                'required' => $user ? false : true,
                'placeholder' => 'S3CR3T',
            ])
            ->endDiv()
            ->addButton('S\'inscrire', ['class' => 'btn btn-primary', 'type' => 'submit'])
            ->endForm();
    }
}