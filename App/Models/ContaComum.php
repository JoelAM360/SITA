<?php

namespace App\Models;

use MF\Model\Model;

class ContaComum extends Usuario
{
    private $num_bi;
    private $upload_bi;
    private $n_proc;
    private $tipo_projeto;
    private $id_projeto;
    private $id_inst;
    private $nome_projeto;
    private $acesso_projeto;


    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function definirAcessoProjeto()
    {
        echo "OK->definirAcessoProjeto";
    }
    public function solicitarAprovacaoProjeto()
    {
        $query = "INSERT INTO `tb_solitacao`(`id_user`, `id_projeto`, `id_inst`, `n_proc`, `curso`,`nome_aluno`, `nome_inst`, `n_bi`) VALUES (:id_user, :id_projeto, :id_inst, :n_proc, :curso, :nome_aluno,  :nome_inst, :n_bi)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id'));
        $stmt->bindValue(':id_projeto', $this->__get('id_projeto'));
        $stmt->bindValue(':id_inst', $this->__get('id_inst'));
        $stmt->bindValue(':n_proc', $this->__get('n_proc'));
        //$stmt->bindValue(':ano', $this->__get('id'));
        $stmt->bindValue(':nome_aluno', $this->__get('nome'));
        $stmt->bindValue(':curso', $this->__get('curso'));
        $stmt->bindValue(':n_bi', $this->__get('num_bi'));
        $stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
        $stmt->execute();

    }




}