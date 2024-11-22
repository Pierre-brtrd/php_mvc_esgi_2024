<?php

namespace App\Controller\Frontend;

use App\Core\Controller;
use App\Core\Route;

class HomeController extends Controller
{
    #[Route('home', '/', ['GET'])]
    public function index(): void
    {
        $this->render('Home/index.php', [
            'meta' => [
                'title' => 'Page d\'accueil'
            ]
        ]);
    }

    #[Route('app.article.show', '/article/([0-9]+)', ['GET'])]
    public function showArticle(int $id): void
    {
        echo "<h1>Article: $id</h1>";
    }
}