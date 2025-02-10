<?php
ob_start(); // Inicia o buffer de saída
session_start();
require_once 'config/conexaoBanco.php';
require_once 'classes/Usuario.php';
require_once 'classes/UsuarioComum.php';
require_once 'classes/UsuarioAdmin.php';

error_log("Iniciando script de login");

// Conectar ao banco de dados
$db = new Conexao();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios.";
        error_log("Campos obrigatórios não preenchidos.");
    } else {
        $query = "SELECT * FROM usuarios WHERE email_usuarios = :email LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuarioData) {
                error_log("Usuário encontrado: " . print_r($usuarioData, true));
            } else {
                error_log("Usuário não encontrado.");
            }

            if ($usuarioData && password_verify($senha, $usuarioData['senha_usuario'])) {
                // Regenerar a sessão para segurança
                session_regenerate_id(true);
                error_log("Senha verificada com sucesso.");

                // Definir o ID do usuário na sessão
                $_SESSION['usuario_id'] = $usuarioData['id_usuarios'];
                $_SESSION['usuario_tipo'] = $usuarioData['tipo_usuario'];
                error_log("Sessão do usuário atualizada: " . print_r($_SESSION, true));

                // Força o redirecionamento
                if (!headers_sent()) {
                    error_log("Redirecionando para principal.php");
                    header('Location: principal.php');
                    exit;
                } else {
                    error_log("Cabeçalhos já foram enviados, não foi possível redirecionar.");
                }
            } else {
                $erro = "E-mail ou senha incorretos.";
                error_log("E-mail ou senha incorretos.");
            }
        } catch (PDOException $e) {
            $erro = "Erro ao acessar o banco de dados: " . $e->getMessage();
            error_log($erro);
        }
    }
}
ob_end_flush(); // Envia o buffer de saída
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header><h1>Login</h1></header>
        <?php if (isset($erro)) { echo "<p style='color:red;'>$erro</p>"; error_log($erro); } ?>
        <form method="POST" action="index.php">
            <label for="email">E-mail:</label>
            <input type="email" name="email" required>
            <br>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
            <br>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
</body>
</html>
