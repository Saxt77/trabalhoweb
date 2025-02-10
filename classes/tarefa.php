<?php
class Tarefa {
    private $id;
    private $titulo;
    private $dataCriacao;
    private $dataConclusao;
    private $status;
    private $progresso;

    public function __construct($titulo) {
        $this->titulo = $titulo;
        $this->dataCriacao = date("Y-m-d H:i:s");
        $this->status = 'Pendente';
        $this->progresso = 0;
    }

    public function concluirTarefa() {
        $this->dataConclusao = date("Y-m-d H:i:s");
        $this->status = 'Concluída';
        $this->progresso = 100;
    }

    public function atualizarProgresso($progresso) {
        $this->progresso = $progresso;
        if ($progresso == 100) {
            $this->concluirTarefa();
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function getDataConclusao() {
        return $this->dataConclusao;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getProgresso() {
        return $this->progresso;
    }
}
?>
