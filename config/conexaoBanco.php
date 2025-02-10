<?php
class Conexao {
    private $host = "localhost";
    private $db_name = "gerenciamento_de_tarefas";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");
            error_log("Conexão com o banco de dados estabelecida com sucesso.");
        } catch (PDOException $exception) {
            error_log("Erro na conexão: " . $exception->getMessage());
            echo "Erro na conexão: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
