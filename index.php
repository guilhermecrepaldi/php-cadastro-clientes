<?php
require_once 'config.php';

$busca = $_GET['busca'] ?? '';
$pagina = (int)($_GET['pagina'] ?? 1);
$limite = 10;
$offset = ($pagina - 1) * $limite;

require_once 'header.php';
?>

<div class="row mb-4">
    <div class="col">
        <h2>Lista de Clientes</h2>
    </div>
    <div class="col text-end">
        <a href="cadastrar.php" class="btn btn-success">+ Novo Cliente</a>
    </div>
</div>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou email..." value="<?= htmlspecialchars($busca) ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Buscar</button>
    </div>
    <div class="col-md-2">
        <a href="index.php" class="btn btn-secondary w-100">Limpar</a>
    </div>
</form>

<?php
$sqlTotal = "SELECT COUNT(*) FROM clientes WHERE nome LIKE :busca OR email LIKE :busca2";
$sqlDados = "SELECT * FROM clientes WHERE nome LIKE :busca OR email LIKE :busca2 LIMIT $limite OFFSET $offset";

$buscaParam = "%$busca%";

$stmt = $pdo->prepare($sqlTotal);
$stmt->execute(['busca' => $buscaParam, 'busca2' => $buscaParam]);
$total = $stmt->fetchColumn();

$stmt = $pdo->prepare($sqlDados);
$stmt->execute(['busca' => $buscaParam, 'busca2' => $buscaParam]);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPaginas = ceil($total / $limite);
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>CPF</th>
                <th>Cidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($clientes): ?>
                <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['telefone']) ?></td>
                    <td><?= htmlspecialchars($c['cpf']) ?></td>
                    <td><?= htmlspecialchars($c['cidade']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="excluir.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPaginas > 1): ?>
<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=<?= $pagina - 1 ?>&busca=<?= urlencode($busca) ?>">Anterior</a>
        </li>
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
            <a class="page-link" href="?pagina=<?= $i ?>&busca=<?= urlencode($busca) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
        <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=<?= $pagina + 1 ?>&busca=<?= urlencode($busca) ?>">Próximo</a>
        </li>
    </ul>
</nav>
<?php endif; ?>

<?php require_once 'footer.php'; ?>
