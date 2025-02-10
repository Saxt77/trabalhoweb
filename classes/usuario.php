<?php
class Usuario {
    protected $id_usuario;
    protected $nome;
    protected $email;
    protected $senha;
    protected $tipo;

    public function __construct($id_usuario, $nome, $email, $senha, $tipo) {
        $this->id_usuario = $id_usuario;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
        $this->tipo = $tipo;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function verificarSenha($senha) {
        return password_verify($senha, $this->senha);
    }
}
?>
