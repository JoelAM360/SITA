<?php 

namespace App\Models;

use MF\Model\Model;

class ContaInst extends Usuario{
    private $id_inst;
    private $aprovar_projeto;
    private $entidade;
    private $faculdade;
    
    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

   
    public function solicitarAprovacaoInst()
    {
        echo "OK->solicitarAprovacaoInst".$this->__get('id');
    }
    public function configurarContaInst()
    {
           
       $query = "INSERT INTO `tb_instituicional`( `nome_inst`, `id_admin`, `entidade`) VALUES (:nome_inst, :id_admin, :entidade)";   
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
       $stmt->bindValue(':id_admin', $this->__get('id'));
       $stmt->bindValue(':entidade', $this->__get('entidade'));
       $stmt->execute();

    }

    public function getInstPorIdAdmin()
    {
       $query = "SELECT id FROM tb_instituicional WHERE id_admin = :id AND nome_inst = :nome_inst";
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':id', $this->__get('id'));
       $stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
       $stmt->execute();
    
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * Pega todos os dados pelo Id do Administrador de intituição ligado com seua dados de user!
     */
    public function getAllInstPorIdAdmin()
    {
       $query = "SELECT inst.id_admin, inst.nome_inst, inst.sobre_inst, inst.entidade , inst.qtd_admin, inst.localizacao, inst.localizacao, us.nome, us.profissao, us.img  FROM `tb_instituicional` AS inst INNER JOIN tb_user AS us ON(inst.id_admin = us.id) WHERE inst.id_admin = :id";
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':id', $this->__get('id'));
       $stmt->execute();
    
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Inserir Cursos a uma Faculdade que pode ou não pertencer a uma universidade
     * Se pertence: o id_falcudade será enviado a partir do método inseririCursos($_POST['entidade])
     * Senão : o id_falcudade receberá o valor default de zero
     * 
     * Após a validação as informações serão add a tb_cursos!
     */
    public function inserirCurso($id_faculdade = 0)
    {
        $query = "INSERT INTO `tb_cursos`( `id_inst`, `nome_curso`,`id_faculdade` ) VALUES (:id_inst, :nome_curso, :id_faculdade)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_inst', $this->__get('id_inst'));
        $stmt->bindValue(':nome_curso', $this->__get('curso'));
        $stmt->bindValue(':id_faculdade', $id_faculdade);
        $stmt->execute();

    }

    public function inserirFaculdade()
    {
        $query = "INSERT INTO `tb_faculdade`( `id_inst`, `nome_faculdade`) VALUES (:id_inst, :nome_faculdade)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_inst', $this->__get('id_inst'));
        $stmt->bindValue(':nome_faculdade', $this->__get('faculdade'));
        $stmt->execute();

    }

    public function atualizarAtiveListGrade($ative_list_grade)
    {
        $query = "UPDATE `tb_instituicional` SET `ative_list_grade`=:ative_list_grade WHERE id =:id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':ative_list_grade', $ative_list_grade);
        $stmt->execute();

    }
    public function getFaculdadePorIdInst()
    {
       $query = "SELECT * FROM `tb_faculdade` AS fa INNER JOIN tb_instituicional AS inst ON( inst.id = fa.id_inst) WHERE inst.id_admin = :id " ;
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':id', $this->__get('id'));
       $stmt->execute();
    
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllCursoPorIdAdminInst()
    {
       $query = "SELECT * FROM `tb_cursos` AS cu INNER JOIN tb_instituicional AS inst ON( inst.id = cu.id_inst) WHERE inst.id_admin = :id " ;
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':id', $this->__get('id'));
       $stmt->execute();
    
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllCursoPorIdFaculdade()
    {
       $query = "SELECT * FROM `tb_cursos` AS cu INNER JOIN tb_faculdade AS fac WHERE fac.id_faculdade = cu.id_faculdade AND cu.id_faculdade =:id_faculdade " ;
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':id_faculdade', $this->__get('id'));
       $stmt->execute();
    
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getCursoPorNomeCurso()
    {
       $query = "SELECT u.id, u.nome, u.tipo_conta, u.img, p.id_projeto, p.nome_projeto, p.nome_instituicao, p.curso, p.data_registro, p.upload, p.qtdLikes  FROM tb_user AS u INNER JOIN tb_projeto AS p ON(u.id = p.id_user)  WHERE u.id = :id AND p.curso=:curso";

       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':id', $this->__get('id'));
       $stmt->bindValue(':curso', $this->__get('curso'));
       $stmt->execute();

       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getProjetoPorCursoInst()
    {
        $query = "SELECT us.id, us.nome, us.tipo_conta, us.img, pr.id_projeto, pr.nome_projeto, pr.nome_instituicao, so.curso, pr.data_registro, pr.upload, pr.qtdLikes FROM `tb_projeto` AS pr INNER JOIN tb_associacao_projeto AS ass ON( pr.id_projeto = ass.id_projeto ) INNER JOIN tb_solitacao AS so ON( so.id_projeto = ass.id_projeto) INNER JOIN tb_user AS us ON(us.id = pr.id_user) WHERE so.nome_inst = :nome_inst AND so.curso = :curso ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
        $stmt->bindValue(':curso', $this->__get('curso'));
        $stmt->execute();
    
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getSolicaitacaoPorInts()
    {
        $query = "SELECT so.id_user, so.id_projeto, so.nome_aluno, so.curso, so.aceitar, so.n_bi, so.n_proc, pr.nome_projeto, pr.upload FROM `tb_solitacao` AS so INNER JOIN tb_projeto AS pr ON(so.id_projeto = pr.id_projeto) WHERE id_inst = :id_inst";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id_inst', $this->__get('id_inst'));
        //$stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
        $stmt->execute();
     
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSolicaitacaoAceitas()
    {
        $query = "SELECT * FROM `tb_solitacao` WHERE aceitar = 'Aprovado' AND id_inst = :id_inst ";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id_inst', $this->__get('id_inst'));
        //$stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
        $stmt->execute();
     
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function aprovarSolicitacao($id_projeto)
    {
        $query ="UPDATE `tb_solitacao` SET `aceitar`= :aprovar_projeto  WHERE tb_solitacao.id_projeto =:id_projeto";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':aprovar_projeto', $this->__get('aprovar_projeto'));
        $stmt->bindValue(':id_projeto', $id_projeto);
        $stmt->execute();

        $this->alunoAssociadoInst($id_projeto);
        
    }
    public function alunoAssociadoInst($id_projeto)
    {
        $query = "INSERT INTO `tb_associacao_projeto`(`id_projeto`) VALUES (:id_projeto)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':id_projeto', $id_projeto);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}