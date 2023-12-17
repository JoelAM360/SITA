<?php

namespace App\Models;

use MF\Model\Model;

class Projeto extends Model
{
    private $id_projeto;
    private $nome_projeto;
    private $id_user;
    private $curso;
    private $area_formacao;
    private $nome_instituicao;
    private $file_upload;
    private $pesquisa;


    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    //Cria Repositorio(Upload de Projeto)
    public function carregarProjeto()
    {
        $file = $_FILES['file_upload'];

        //Armazenando os valores um suas variaveis
        $file_name = $file['name'];
        $file_erro = $file['error'];
        $file_size = $file['size'];
        $tmp_name = $file['tmp_name'];
        //Verficar se ñ há nenhum erro
        if ($file_erro === 0) {
            //Pegar extensões e guardar:
            $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);

            //Converter para minuscula:
            $file_ex_lc = strtolower($file_ex);

            //Array de extensões validas e validar extensões
            $array_exs = array('docx', 'ppt', 'pdf');

            if (in_array($file_ex_lc, $array_exs)) {
                //Renomear Img com a extensão
                $new_file_name = uniqid('DOC-', true) . '.' . $file_ex_lc;
                $this->__set('file_upload', $new_file_name);
                $query = "INSERT INTO `tb_projeto`(`id_user`, `nome_projeto`, `curso`, `area_formacao`, upload) VALUES (:id_user, :nome_projeto, :curso, :area_formacao, :file_upload)";

                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':nome_projeto', $this->__get('nome_projeto'));
                $stmt->bindValue(':id_user', $this->__get('id_user'));
                $stmt->bindValue(':curso', $this->__get('curso'));
                $stmt->bindValue(':area_formacao', $this->__get('area_formacao'));
                $stmt->bindValue(':file_upload', $this->__get('file_upload'));
                //$stmt->bindValue(':nome_instituicao', $this->__get('nome_instituicao')); 

                $stmt->execute();

                //Mover uma pasta no APP:
                $destination_path = getcwd() . DIRECTORY_SEPARATOR;
                move_uploaded_file($tmp_name, $destination_path . '/app/assets/upload/' . $new_file_name);

                if ($_SESSION['tipo_conta'] == "ContaComum") {
                    return "/salvo?upload=true";
                } else {
                    return "/salvoInst?upload=true";
                }


            }
        }
    }
    //Recuperar todos os Projetos para lista-los
    public function getAllProjet()
    {
        $query = "SELECT pr.id_projeto, pr.id_user, u.nome, u.img, pr.nome_projeto, pr.upload, pr.qtdLikes FROM tb_projeto AS pr INNER JOIN tb_user AS u ON (pr.id_user = u.id)";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllProjetUser()
    {
        $query = "SELECT * FROM tb_projeto WHERE id_user = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id_user'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOnlyOneProjet()
    {
        $query = "SELECT * FROM tb_projeto WHERE id_projeto = :id_projeto";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_projeto', $this->__get('id_projeto'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    //Recuperar Projetos Pesquisados para lista-los
    public function searchProjet()
    {
        $query = "SELECT pr.id_user, pr.id_projeto, u.nome, u.img, pr.curso, pr.nome_instituicao, pr.nome_projeto, pr.upload, pr.qtdLikes FROM tb_projeto AS pr INNER JOIN tb_user AS u ON (pr.id_user = u.id) WHERE pr.nome_projeto LIKE :pesquisa1 OR pr.curso LIKE :pesquisa3";

        $stmt = $this->db->prepare($query);
        //$stmt->bindValue(':pesquisa', "%".$this->__get('pesquisa')."%");
        $stmt->bindValue(':pesquisa1', "%" . $this->__get('pesquisa') . "%");
        // $stmt->bindValue(':pesquisa2', "%".$this->__get('pesquisa')."%");
        $stmt->bindValue(':pesquisa3', "%" . $this->__get('pesquisa') . "%");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function avaliarProjeto($acao)
    {

        if ($acao == "curtir") {
            $query = "INSERT INTO `tb_likes`(`id_projeto`, `id_user`, `likes`) VALUES (:id_projeto, :id_user, 1)";
        } else {
            $query = "DELETE FROM `tb_likes` WHERE `id_projeto` =:id_projeto AND id_user = :id_user";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->bindValue(':id_projeto', $this->__get('id_projeto'));

        $stmt->execute();

        /**
         * Pegar a quantidade de Likes e armazenar na variavel $qtdLikes(Var que representa a quatideda de likes)
         */
        $qtdLikes = $this->qtdLikesPorProjeto($this->__get('id_projeto'))[0]["qtdLikes"];

        /**
         * Após Pegar qtdLikes Settar fazendo o Update da tb_projetos
         */
        $this->setQtdLikesProjeto($qtdLikes, $this->__get('id_projeto'));

    }

    public function checkAvailiacao()
    {
        $query = "SELECT * FROM tb_likes WHERE id_user = :id_user";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $json_result = json_encode($results);

        return $json_result;
    }

    public function qtdLikesPorProjeto($id_projeto)
    {
        $query = "SELECT COUNT(*) AS qtdLikes FROM tb_likes WHERE id_projeto = :id_projeto";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_projeto', $id_projeto);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function setQtdLikesProjeto($qtdLikes, $id_projeto)
    {
        $query = "UPDATE `tb_projeto` SET `qtdLikes`= :qtdLikes WHERE `id_projeto`= :id_projeto";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':qtdLikes', $qtdLikes);
        $stmt->bindValue(':id_projeto', $id_projeto);
        $stmt->execute();

    }
    public function excluirProjeto()
    {
        $query = "DELETE FROM `tb_projeto` WHERE id_projeto = :id_projeto AND id_user = :id_user";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_projeto', $this->__get('id_projeto'));
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->execute();
    }
    public function editarDadosProjeto()
    {
        $query = " UPDATE `tb_projeto` SET nome_projeto=:nome_projeto WHERE id_projeto =:id_projeto ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_projeto', $this->__get('id_projeto'));
        $stmt->bindValue(':nome_projeto', $this->__get('nome_projeto'));
        $stmt->execute();
    }



}
?>