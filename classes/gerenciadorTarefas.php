<?php
require_once 'tarefa.php';

class GerenciadorDeTarefas {
    private $tarefas = [];

    public function adicionarTarefa($tarefa) {
        $this->tarefas[] = $tarefa;
    }

    public function removerTarefa($idTarefa) {
        foreach ($this->tarefas as $key => $tarefa) {
            if ($tarefa->getId() == $idTarefa) {
                unset($this->tarefas[$key]);
            }
        }
    }

    public function listarTarefas() {
        return $this->tarefas;
    }
}
?>
