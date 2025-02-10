<?php
require_once 'Usuario.php';

class UsuarioAdmin extends Usuario {
    public function __construct($id_usuario, $nome, $email, $senha) {
        parent::__construct($id_usuario, $nome, $email, $senha, 'administrador');
    }
}
?>
