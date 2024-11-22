<?php

namespace App\Core;

abstract class Form
{
    protected string $formCode = '';

    /**
     * Validation of form
     * @param array $form array with submitted data ($_POST)
     * @param array $champs array with fields to validate
     * @return bool true if form is valid, false otherwise
     */
    public static function validate(array $form, array $champs): bool
    {
        if (!isset($form['_token']) || !hash_equals($_SESSION['token'] ?? '', $form['_token'])) {
            $_SESSION['flash']['danger'] = "Invalid token CSRF";

            return false;
        }

        foreach ($champs as $champ) {
            if (!isset($form[$champ]) || empty($form[$champ]) || strlen(trim($form[$champ])) === 0) {
                return false;
            }
        }

        return true;
    }

    public function startForm(string $action = "#", string $method = "POST", array $attributs = []): static
    {
        $this->formCode .= "<form action=\"$action\" method=\"$method\"{$this->addHtmlAttributes($attributs)}>";

        return $this;
    }

    public function startDiv(array $attributs = []): static
    {
        $this->formCode .= "<div{$this->addHtmlAttributes($attributs)}>";

        return $this;
    }

    public function addLabel(string $for, string $text, array $attributs = []): static
    {
        $this->formCode .= "<label for=\"$for\"{$this->addHtmlAttributes($attributs)}>$text</label>";

        return $this;
    }

    public function addInput(string $type, string $name, array $attributs = []): static
    {
        $this->formCode .= "<input type=\"$type\" name=\"$name\"{$this->addHtmlAttributes($attributs)}/>";

        return $this;
    }

    public function addTextArea(string $name, array $attributs = [], ?string $value = ''): static
    {
        $this->formCode .= "<textarea name=\"$name\"{$this->addHtmlAttributes($attributs)}>{$value}</textarea>";

        return $this;
    }

    public function addButton(string $label, array $attributs = []): static
    {
        $this->formCode .= "<button{$this->addHtmlAttributes($attributs)}>$label</button>";

        return $this;
    }

    public function endDiv(): static
    {
        $this->formCode .= "</div>";

        return $this;
    }

    public function endForm(): static
    {
        $_SESSION['token'] = bin2hex(random_bytes(64));

        $this->formCode .= "<input type=\"hidden\" name=\"_token\" value=\"{$_SESSION['token']}\">";

        $this->formCode .= "</form>";

        return $this;
    }

    protected function addHtmlAttributes(array $attributs = []): string
    {
        $str = '';

        $shortAttributs = ['novalidate', 'required', 'autocomplete', 'autofocus', 'enctype', 'target', 'checked', 'disabled', 'multiple', 'readonly'];

        foreach ($attributs as $key => $value) {
            if($value) {
                if (in_array($key, $shortAttributs)) {
                    $str .= " $key";
                } else {
                    $str .= " $key=\"$value\"";
                }
            }
        }

        return $str;
    }

    public function create(): string
    {
        return $this->formCode;
    }
}