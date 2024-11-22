<section class="container mt-4">
    <h1 class="text-center">Administration des users</h1>
   <div class="table-responsive">
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nom Complet</th>
                <th scope="col">Email</th>
                <th scope="col">Date de création</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user):?>
                <tr scopde="row">
                    <td><?= $user->getId();?></td>
                    <td><?= $user->getFullName();?></td>
                    <td><?= $user->getEmail();?></td>
                    <td><?= $user->getCreatedAt()->format('d-m-Y');?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="/admin/users/<?= $user->getId();?>/edit" class="btn btn-warning">Modifier</a>
                            <form action="/admin/users/<?= $user->getId();?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                <input type="hidden" name="token" value="<?= hash('sha512','user-' . $user->getId());?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
        </tbody>
    </table>
   </div>
   
</section>