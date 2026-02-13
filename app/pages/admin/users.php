<?php
require_admin();
require_once __DIR__ . '/../../models/User.php';

$pdo = db($config);
$users = user_all($pdo);

require __DIR__ . '/../../views/partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center mt-3">
  <h1 class="h3 mb-0">Utilisateurs</h1>
</div>

<div class="table-responsive mt-3">
  <table class="table align-middle">
    <thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Créé</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= e($u['nom']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['role']) ?></td>
          <td><?= e($u['created_at']) ?></td>
          <td class="text-end">
            <?php if ($u['role'] !== 'admin'): ?>
              <a class="btn btn-sm btn-outline-danger" href="<?= e(url('/index.php?page=admin_user_delete&id=' . (int)$u['id'])) ?>">Supprimer</a>
            <?php else: ?>
              <span class="text-muted">Protégé</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../../views/partials/footer.php';
