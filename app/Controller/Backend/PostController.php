<?php

namespace App\Controller\Backend;

use App\Core\Controller;
use App\Core\Form;
use App\Core\Route;
use App\Form\Backend\PostForm;
use App\Model\Poste;

class PostController extends Controller
{
    #[Route('admin.posts.index', '/admin/posts', ['GET'])]
    public function index(): void
    {
        $posts = (new Poste())->findAll();

        $this->render('Backend/Posts/index.php', [
            'meta' => [
                'title' => 'Admin - Liste des posts',
            ],
            'posts' => $posts
        ]);
    }

    #[Route('admin.posts.create', '/admin/posts/create', ['GET', 'POST'])]
    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Form::validate($_POST, ['titre', 'description'])) {
            $post = (new Poste())
                ->hydrate($_POST);

            if (!(new Poste())->findOneByTitle($post->getTitre())) {
                $post->create();

                $this
                    ->addFlash('success', 'Le post a bien été créé')
                    ->redirect('/admin/posts');
            } else {
                $this->addFlash('danger', 'Un post avec ce titre existe déjà');
            }
        }

        $form = new PostForm('/admin/posts/create');

        $this->render('Backend/Posts/create.php', [
            'meta' => [
                'title' => 'Admin - Créer un post',
            ],
            'form' => $form->create(),
        ]);
    }

    #[Route('admin.posts.update', '/admin/posts/([0-9]+)/edit', ['GET', 'POST'])]
    public function update(int $id): void
    {
        $post = (new Poste())->find($id);

        if (!$post) {
            $this
                ->addFlash('danger', 'Le post n\'existe pas')
                ->redirect('/admin/posts');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Form::validate($_POST, ['titre', 'description'])) {
            $actif = (isset($_POST['actif']) && $_POST['actif'] == 'on') ? true : false;
            $_POST['actif'] = $actif;
            $post = $post->hydrate($_POST);

            $titleExists = (new Poste())->findOneByTitle($post->getTitre())
                && (new Poste())->findOneByTitle($post->getTitre())->getId() !== $post->getId();

            if (!$titleExists) {
                $post->update();

                $this
                    ->addFlash('success', 'Le post a bien été modifié')
                    ->redirect('/admin/posts');
            } else {
                $this
                    ->addFlash('danger', 'Un post avec ce titre existe déjà')
                    ->redirect($_SERVER['REQUEST_URI'])
                ;
            }
        }

        $form = new PostForm($_SERVER['REQUEST_URI'], $post);
        $this->render('Backend/Posts/update.php', [
            'meta' => [
                'title' => 'Admin - Modifier un post',
            ],
            'titre' => $post->getTitre(),
            'form' => $form->create(),
        ]);
    }

    #[Route('admin.posts.delete', '/admin/posts/([0-9]+)/delete', ['POST'])]
    public function delete(int $id): void
    {
        $post = (new Poste())->find($id);

        if (!$post) {
            $this
                ->addFlash('danger', 'Post non trouvé')
                ->redirect('/admin/posts');
        }

        if (hash_equals(hash('sha512', "post-{$post->getId()}"), $_POST['token'])) {
            $post->delete();

            $this
                ->addFlash('success', 'Post supprimé avec succès')
                ->redirect('/admin/posts');
        }

        $this
            ->addFlash('danger', 'Invalid CSRF token')
            ->redirect('/admin/posts');
    }
}