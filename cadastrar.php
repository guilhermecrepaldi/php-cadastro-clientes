<?php
require_once 'config.php';
require_once 'helpers/valida_cpf.php';

$erros = [];
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $cep = trim($_POST['cep'] ?? '');

    if (empty($nome)) $erros[] = 'Nome é obrigatório.';
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'Email inválido.';
    if (!empty($cpf) && !valida_cpf($cpf)) $erros[] = 'CPF inválido.';

    if (empty($erros)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, telefone, cpf, endereco, cidade, estado, cep) VALUES (:nome, :email, :telefone, :cpf, :endereco, :cidade, :estado, :cep)");
            $stmt->execute([
                'nome' => $nome,
                'email' => $email ?: null,
                'telefone' => $telefone ?: null,
                'cpf' => $cpf ?: null,
                'endereco' => $endereco ?: null,
                'cidade' => $cidade ?: null,
                'estado' => $estado ?: null,
                'cep' => $cep ?: null,
            ]);
            $sucesso = 'Cliente cadastrado com sucesso!';
        } catch (PDOException $e) {
            $erros[] = 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }
}

require_once 'header.php';
?>

<h2>Cadastrar Cliente</h2>

<?php if ($sucesso): ?>
<div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<?php if ($erros): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($erros as $erro): ?>
        <li><?= $erro ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" class="row g-3">
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome *</label>
        <input type="text" name="nome" id="nome" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control">
    </div>
    <div class="col-md-4">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" name="telefone" id="telefone" class="form-control">
    </div>
    <div class="col-md-4">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" name="cpf" id="cpf" class="form-control">
    </div>
    <div class="col-md-4">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" name="cep" id="cep" class="form-control">
    </div>
    <div class="col-md-12">
        <label for="endereco" class="form-label">Endereço</label>
        <textarea name="endereco" id="endereco" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-md-6">
        <label for="cidade" class="form-label">Cidade</label>
        <input type="text" name="cidade" id="cidade" class="form-control">
    </div>
    <div class="col-md-2">
        <label for="estado" class="form-label">Estado</label>
        <input type="text" name="estado" id="estado" class="form-control" maxlength="2">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once 'footer.php'; ?>
