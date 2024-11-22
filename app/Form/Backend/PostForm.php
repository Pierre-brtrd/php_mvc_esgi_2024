<?php

namespace App\Form\Backend;

use App\Core\Form;
use App\Model\Poste;

class PostForm extends Form
{
    public function __construct(string $action, ?Poste $poste = null)
    {
        $this
            ->startForm($action, 'POST', ['class' => 'card p-4 mt-3'])

            // Start Titre
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('title', 'Titre', ['class' => 'form-label'])
            ->addInput('text', 'titre', [
                'class' => 'form-control',
                'required' => true,
                'id' => 'title',
                'placeholder' => 'Mon super titre',
                'value' => $poste ?->getTitre()
            ])
            ->endDiv()
            // End Titre

            // Start Description
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('description', 'Description', ['class' => 'form-label'])
            ->addTextArea('description', [
                'class' => 'form-control',
                'id' => 'description',
                'required' => true,
                'placeholder' => 'Ma super description',
            ], $poste ?->getDescription())
            ->endDiv()
            // End Description

            // Start Actif
            ->startDiv(['class' => 'mb-3 form-check form-switch'])
            ->addInput('checkbox', 'actif', [
                'class' => 'form-check-input',
                'id' => 'actif',
                'role' => 'switch',
                'checked' => (bool) $poste ?->getActif()
            ])
            ->addLabel('actif', 'Actif ?', ['class' => 'form-check-label'])
            ->endDiv()
            // End Actif

            ->addButton($poste ? 'Enregistrer' : 'Créer', ['class' => 'btn btn-primary', 'type' => 'submit'])
            ->endForm()
        ;
    }
}