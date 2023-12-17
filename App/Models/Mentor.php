<?php

namespace App\Models;

use MF\Model\Model;

class Mentor extends Model
{

    private $tipo_mentoria;
    private $bi;
    private $habilitacao;
    private $id_mentor;


    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    //Método para criar Conta
    public function cadastarMentor($id_user)
    {
        $query = "INSERT INTO `tb_mentor`(`id_user`, `tipo_mentori`, `bi`, `habilitacao`) VALUES (:id_user, :tipo_mentori, :bi, :habilitacao)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $id_user);
        $stmt->bindValue(':tipo_mentori', $this->__get('tipo_mentoria'));
        $stmt->bindValue(':bi', $this->__get('bi'));
        $stmt->bindValue(':habilitacao', $this->__get('habilitacao'));

        //Mover uma pasta no APP:
        //$destination_path = getcwd().DIRECTORY_SEPARATOR;
        //move_uploaded_file($tmp_name, $destination_path.'/app/assets/img/'.$new_img_name);

        if ($stmt->execute()) {
            return true;

        } else {
            return false;

        }

    }

    //Método para criar Conta
    public function solicitarAssociacaoMentoria($id_aluno, $comprativo, $pre_projeto)
    {
        $query = "INSERT INTO `tb_ass_mentor_aluno`(`id_mentor`, `id_aluno`, `comprativo`, `tipo_metoria`, `pre_projeto`) VALUES (:id_mentor, :id_aluno, :comprativo, :tipo_metoria, :pre_projeto)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_aluno', $id_aluno);
        $stmt->bindValue(':tipo_metoria', $this->__get('tipo_mentoria'));
        $stmt->bindValue(':id_mentor', $this->__get('id_mentor'));
        $stmt->bindValue(':comprativo', $comprativo);
        $stmt->bindValue(':pre_projeto', $pre_projeto);

        //Mover uma pasta no APP:
        //$destination_path = getcwd().DIRECTORY_SEPARATOR;
        //move_uploaded_file($tmp_name, $destination_path.'/app/assets/img/'.$new_img_name);

        if ($stmt->execute()) {
            return true;

        } else {
            return false;

        }

    }

    //Recuperar um user por e-mail
    public function getMentorPorIdUser($id_user)
    {
        $query = "SELECT * FROM `tb_mentor` WHERE id_user =:id_user";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $id_user);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getAllMentores()
    {
        $query = "SELECT * FROM `tb_mentor` as m ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getAllMentor($id_user)
    {
        $query = "SELECT *,(SELECT count(*) FROM tb_seguir as s WHERE s.id_seguidor = :id_user  AND s.id_seguindo = me.id_user) as seguindo_sn FROM `tb_mentor` AS me INNER JOIN tb_user AS us ON(me.id_user = us.id) WHERE us.id != :id_user GROUP BY me.id_mentor LIMIT 8";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $id_user);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getAllComJoin()
    {
        $query = "SELECT * FROM `tb_mentor` AS me INNER JOIN tb_user AS us ON(me.id_user = us.id) GROUP BY me.id_mentor LIMIT 8";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getAlunoDeUmMentor($id_user)
    {
        $query = "SELECT * FROM `tb_mentor` AS me INNER JOIN tb_ass_mentor_aluno AS ass INNER JOIN tb_user AS us ON(us.id = ass.id_aluno) WHERE me.id_user =:id_user";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $id_user);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function uploadFiles($caminho, $img)
    {

        //Armazenando os valores um suas variaveis
        $file_name = $img['name'];
        $file_erro = $img['error'];
        $file_size = $img['size'];
        $tmp_name = $img['tmp_name'];

        //Verficar se ñ há nenhum erro
        if ($file_erro === 0) {
            //Pegar extensões e guardar:
            $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);

            //Converter para minuscula:
            $file_ex_lc = strtolower($file_ex);

            //Array de extensões validas e validar extensões
            $array_exs = array('jpeg', 'jpg', 'png', 'pdf');

            if (in_array($file_ex_lc, $array_exs)) {
                $new_img_name = uniqid('DOC-', true) . '.' . $file_ex_lc;
                //Mover uma pasta no APP:
                $destination_path = getcwd() . DIRECTORY_SEPARATOR;
                if (move_uploaded_file($tmp_name, $destination_path . $caminho . $new_img_name)) {
                    return $new_img_name;
                }

            }

        }

    }

}
?>