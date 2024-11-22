<section class="container mt-4">
    <h1 class="text-center">Administration des posts</h1>
    <div class="d-flex my-2">
        <a href="/admin/posts/create" class="ms-auto btn btn-primary">Créer</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead class="table-primary">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Titre</th>
                <th scope="col">Description</th>
                <th scope="col">Actif ?</th>
                <th scope="col">Date de création</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($posts as $post):?>
                <tr scopde="row">
                    <td><?= $post->getId();?></td>
                    <td><?= $post->getTitre();?></td>
                    <td><?= $post->getDescription();?></td>
                    <td><?= $post->getActif() === false ? 'Non' : 'Oui'; ?></td>
                    <td><?= $post->getCreatedAt()->format('d-m-Y');?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="/admin/posts/<?= $post->getId();?>/edit" class="btn btn-warning">Modifier</a>
                            <form action="/admin/posts/<?= $post->getId();?>/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?')">
                                <input type="hidden" name="token" value="<?= hash('sha512','post-' . $post->getId());?>">
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