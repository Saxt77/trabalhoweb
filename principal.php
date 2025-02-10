<?php
session_start();
require_once 'config/conexaoBanco.php';

$db = new Conexao();
$conn = $db->getConnection();

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redirecione o usuário para a página de cadastro (login)
    header('Location: cadastro.php');
    exit;
}

$id_usuarios = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'criar') {
        $titulo = trim($_POST['titulo']);
        $status = 'pendente';
        $progresso = intval($_POST['progresso']);
        $data_criacao = date('Y-m-d H:i:s');
        $data_conclusao = !empty($_POST['data_conclusao']) ? $_POST['data_conclusao'] : null;

        if (empty($titulo)) {
            $erro = "O título da tarefa é obrigatório.";
        } else {
            try {
                $query = "INSERT INTO tarefas (id_usuarios, titulo, data_criacao, data_conclusao, status, progresso) VALUES (:id_usuarios, :titulo, :data_criacao, :data_conclusao, :status, :progresso)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':id_usuarios', $id_usuarios);
                $stmt->bindParam(':titulo', $titulo);
                $stmt->bindParam(':data_criacao', $data_criacao);
                if ($data_conclusao === null) {
                    $stmt->bindValue(':data_conclusao', null, PDO::PARAM_NULL);
                } else {
                    $stmt->bindParam(':data_conclusao', $data_conclusao);
                }
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':progresso', $progresso);
                $stmt->execute();
                header('Location: principal.php');
                exit;
            } catch (PDOException $e) {
                echo "Erro ao inserir tarefa: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
        $id_tarefas = $_POST['id_tarefas'];
        
        try {
            $query = "DELETE FROM tarefas WHERE id_tarefas = :id_tarefas";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_tarefas', $id_tarefas);
            $stmt->execute();
            header('Location: principal.php');
            exit;
        } catch (PDOException $e) {
            echo "Erro ao excluir tarefa: " . $e->getMessage();
        }
    }
}

// Buscar tarefas para o usuário atual
$query = "SELECT * FROM tarefas WHERE id_usuarios = :id_usuarios";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_usuarios', $id_usuarios);
$stmt->execute();
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header><h1>GERENCIADOR DE TAREFAS</h1></header>
        
        <?php if (isset($erro)) { echo "<p style='color:red;'>$erro</p>"; } ?>

        <section>
            <h2>Adicionar Nova Tarefa</h2>
            <form method="POST" action="principal.php">
                <input type="hidden" name="acao" value="criar">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" required>
                <label for="data_criacao">Data de Criação:</label>
                <input type="datetime-local" name="data_criacao" required>
                <label for="data_conclusao">Data de Conclusão:</label>
                <input type="datetime-local" name="data_conclusao">
                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="pendente">Pendente</option>
                    <option value="andamento">Andamento</option>
                    <option value="concluido">Concluído</option>
                </select>
                <label for="progresso">Progresso (%):</label>
                <input type="number" name="progresso" value="0" min="0" max="100" required>
                <button type="submit">Adicionar Tarefa</button>
            </form>
        </section>

        <section>
            <h2>Lista de Tarefas</h2>
            <ul class="task-list">
                <?php foreach ($tarefas as $tarefa) { ?>
                    <li class="task-item">
                        <span class="task-title">Título: <?php echo htmlspecialchars($tarefa['titulo']); ?></span>
                        <span class="task-created">Data de Criação: <?php echo htmlspecialchars($tarefa['data_criacao']); ?></span>
                        <span class="task-due">Data de Conclusão: <?php echo htmlspecialchars($tarefa['data_conclusao']); ?></span>
                        <span class="task-status">Status: <?php echo ucfirst($tarefa['status']); ?></span>
                        <span class="task-progress">Progresso: <?php echo $tarefa['progresso']; ?>%</span>
                        <form method="POST" action="principal.php" style="display:inline;">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id_tarefas" value="<?php echo $tarefa['id_tarefas']; ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        </section>

        <br>

        <p>para sair clique em <a href="logout.php">Sair</a></p><br>
    </div>
</body>
</html>
