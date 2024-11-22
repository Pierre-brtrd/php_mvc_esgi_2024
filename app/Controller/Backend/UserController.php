<?php 

namespace App\Controller\Backend;

use App\Core\Controller;
use App\Core\Form;
use App\Core\Route;
use App\Form\UserForm;
use App\Model\User;

class UserController extends Controller
{
    #[Route('admin.users.index', '/admin/users', ['GET'])]
    public function index(): void
    {
        $this->render('Backend/Users/index.php', [
            'meta' => [
                'title' => 'Administration des users',
            ],
            'users' => (new User)->findAll(),
        ]);
    }

    #[Route('admin.users.update', '/admin/users/([0-9]+)/edit', ['GET', 'POST'])]
    public function update(int $id): void
    {
        $user = (new User)->find($id);

        if (!$user) {
            $this
                ->addFlash('danger', 'User non trouvé')
                ->redirect('/admin/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Form::validate($_POST, ['firstName', 'lastName', 'email'])) {
            $user = $user->hydrate($_POST);

            $emailExist = (new User)->findOneByEmail($user->getEmail())
                && (new User)->findOneByEmail($user->getEmail())->getId() !== $user->getId();

            if (!$emailExist) {
                if(!empty($_POST['password'])) {
                    $user
                    ->setPassword(
                        password_hash($_POST['password'], PASSWORD_ARGON2I),
                    );
                }

                $user
                    ->update();

                    $this->addFlash('success', 'User Modifié avec succès')
                        ->redirect('/admin/users');
            } else {
                $this->addFlash('danger', 'Cet email est déjà utilisé par un autre compte');
            }
        }

        $form = new UserForm($_SERVER['REQUEST_URI'], $user);

        $this->render('Backend/Users/update.php', [
            'meta' => [
                'title' => "Modification du user {$user->getFullName()}"
            ],
            'user' => $user,
            'form' => $form->create(),
        ]);
    }

    #[Route('admin.users.delete', '/admin/users/([0-9]+)', ['POST'])]
    public function delete(int $id): void
    {
        $user = (new User)->find($id);

        if (!$user) {
            $this
                ->addFlash('danger', 'User non trouvé')
                ->redirect('/admin/users');
        }
        
        if (hash_equals(hash('sha512', "user-{$user->getId()}"), $_POST['token'])) {
            $user->delete();

            $this
                ->addFlash('success', 'User supprimé avec succès')
                ->redirect('/admin/users');
        }

        $this
            ->addFlash('danger', 'Invalid CSRF token')
            ->redirect('/admin/users');
    }
}