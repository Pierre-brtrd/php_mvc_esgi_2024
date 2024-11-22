<?php

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        ob_start();

        require_once ROOT . "/Views/$view";

        $content = ob_get_clean();

        require_once ROOT . '/Views/base.php';
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        http_response_code($statusCode);
        header("Location: $url");
        exit;
    }

    protected function addFlash(string $type, string $message): static
    {
        $_SESSION['flash'][$type] = $message;

        return $this;
    }
}