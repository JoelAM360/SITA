<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model
{

    private $id;
    private $nome;
    private $nome_inst;
    private $curso;
    private $email;
    private $senha;
    private $telefone;
    private $img;
    private $tipo_conta;
    private $localizacao;
    private $sobre;
    private $pais;
    private $profissao;
    private $link_fb;


    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    //Método para criar Conta
    public function salvar()
    {
        $images = $_FILES['image'];

        //Armazenando os valores um suas variaveis
        $img_name = $images['name'];
        $img_erro = $images['error'];
        $img_size = $images['size'];
        $tmp_name = $images['tmp_name'];
        //Verficar se ñ há nenhum erro
        if ($img_erro === 0) {
            //Pegar extensões e guardar:
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

            //Converter para minuscula:
            $img_ex_lc = strtolower($img_ex);

            //Array de extensões validas e validar extensões
            $array_exs = array('jpg', 'jpeg', 'png', 'pdf');

            if (in_array($img_ex_lc, $array_exs)) {
                //Renomear Img com a extensão
                $new_img_name = uniqid('IMG-', true) . '.' . $img_ex_lc;
                $this->__set('img', $new_img_name);

                $query = "INSERT INTO tb_user( nome, email, senha, img, tipo_conta) VALUES (:nome, :email, :senha, :img, :tipo_conta)";

                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':nome', $this->__get('nome'));
                $stmt->bindValue(':email', $this->__get('email'));
                $stmt->bindValue(':tipo_conta', $this->__get('tipo_conta'));
                $stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
                $stmt->bindValue(':img', $this->__get('img'));
                $stmt->execute();

                //Mover uma pasta no APP:
                $destination_path = getcwd() . DIRECTORY_SEPARATOR;
                move_uploaded_file($tmp_name, $destination_path . '/app/assets/img/' . $new_img_name);
                return $this;

            }
        }

    }

    //Validar se um cadastro pode ser feito
    public function validarCadastro()
    {
        $validar = true;

        if (empty($this->__get('nome')) || empty($this->__get('email')) || empty($this->__get('senha'))) {
            $validar = false;
        }
        return $validar;
    }
    //Recuperar um user por e-mail
    public function getUserPorEmail()
    {
        $query = "SELECT * FROM tb_user WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    //Recuperar um user por e-mail
    public function getAllUser()
    {
        $query = "SELECT * FROM tb_user";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    //Autenticar User
    public function autenticar()
    {
        $query = "SELECT id, nome, email, img, tipo_conta FROM tb_user WHERE email = :email and senha = :senha";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Pesquisar por Projetos e Recuperar para lista-los na resposta
    public function searchUser()
    {
        $query = "SELECT u.id, u.nome, u.tipo_conta, u.img,(SELECT count(*) FROM tb_seguir as s WHERE s.id_seguidor = :id  AND s.id_seguindo = u.id) as seguindo_sn 
        FROM tb_user as u WHERE u.nome LIKE :nome AND u.id != :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', "%" . $this->__get('nome') . "%");
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Pesquisar por Projetos e Recuperar para lista-los na resposta, sem estar logado
    public function searchUserSemLogin()
    {
        $query = "SELECT u.id, u.nome, u.tipo_conta, u.img,(SELECT count(*) FROM tb_seguir as s WHERE s.id_seguidor = :id  AND s.id_seguindo = u.id) as seguindo_sn 
        FROM tb_user as u WHERE u.nome LIKE :nome";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', "%" . $this->__get('nome') . "%");
        $stmt->bindValue(':id', "");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Pesquisar por Instituições para lista-los na resposta e caputrando se está seguindo ou não o(s) mesmo(s)
    public function searchInstituicao()
    {
        $query = "SELECT u.id, u.nome, u.tipo_conta, inst.nome_inst, u.img,(SELECT count(*) FROM tb_seguir as s WHERE s.id_seguidor = :id AND s.id_seguindo = u.id) as seguindo_sn 
        FROM tb_user as u INNER JOIN tb_instituicional AS inst ON(inst.id_admin = u.id) 
        WHERE inst.nome_inst LIKE :nome AND u.id != :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', "%" . $this->__get('nome') . "%");
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Pesquisar por Instituições para lista-los na resposta, sem estar logado
    public function searchInstituicaoSemLogin()
    {
        $query = "SELECT u.id, u.nome, u.tipo_conta, inst.nome_inst, u.img,(SELECT count(*) FROM tb_seguir as s WHERE s.id_seguidor = :id AND s.id_seguindo = u.id) as seguindo_sn 
            FROM tb_user as u INNER JOIN tb_instituicional AS inst ON(inst.id_admin = u.id) 
            WHERE inst.nome_inst LIKE :nome";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', "%" . $this->__get('nome') . "%");
        $stmt->bindValue(':id', );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Perfil do User q deseja recuperar os dados e lista-los. Usado o momento de visitar um perfil
    public function perfil()
    {
        $query = "SELECT u.id, u.nome, u.tipo_conta, u.img, u.profissao, u.instituicao, u.sobre, p.id_projeto, p.nome_projeto, p.nome_instituicao, p.curso, p.data_registro, p.upload, p.qtdLikes  FROM tb_user AS u INNER JOIN tb_projeto AS p ON u.id=p.id_user  WHERE id = :id ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Perfil do User que direciona ao campo de edição
    public function visitarPerfil()
    {
        $query = "SELECT nome, id, email, img, tipo_conta, qtdSeguidores, curso, instituicao, sobre, telefone FROM tb_user WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Inserir Seguidor
    public function seguir($acao, $id_seguindo)
    {

        if ($acao == "seguir") {
            $query = "INSERT INTO `tb_seguir`(`id_seguidor`, `id_seguindo`) VALUES (:id_seguidor, :id_seguindo)";
        } else {
            $query = "DELETE FROM `tb_seguir` WHERE id_seguindo=:id_seguindo AND id_seguidor = :id_seguidor";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_seguidor', $this->__get('id'));
        $stmt->bindValue(':id_seguindo', $id_seguindo);

        if ($stmt->execute()) {
            return "OK";

        } else {
            return "Erro";

        }


    }
    public function upadteQtdSeguidores($qtdSeg, $id_seguidor)
    {
        $query = "UPDATE `tb_user` SET qtdSeguidores = :qtdSeg WHERE id = :id_seguidor";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_seguidor', $id_seguidor);
        $stmt->bindValue(':qtdSeg', $qtdSeg);
        $stmt->execute();

    }

    public function sugestoesPerfil()
    {
        $query = "SELECT * FROM `tb_user` ORDER BY `tb_user`.`qtdSeguidores`  DESC LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
    public function qtdSeguindoPorID($id)
    {
        $query = "SELECT * FROM `tb_seguir` WHERE id_seguindo = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    //Recuperar IDs para Validação
    public function getID($id_seguindo)
    {
        $query = "SELECT * FROM `tb_seguir` WHERE id_seguidor = :id_seguidor AND id_seguindo = :id_seguindo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_seguidor', $this->__get('id'));
        $stmt->bindValue(':id_seguindo', $id_seguindo);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
    public function editarConta()
    {
        if ($this->__get('tipo_conta') == "Conta Comum") {

            $query = "UPDATE `tb_user` SET `nome`=:nome,`email`=:email,`instituicao`=:nome_inst,`sobre`=:sobre,`curso`=:curso,`profissao`=:profissao,`facebook`=:link_fb,`endereco`=:localizacao,`telefone`=:telefone WHERE id=:id";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
            $stmt->bindValue(':curso', $this->__get('curso'));
            $stmt->bindValue(':telefone', $this->__get('telefone'));
            $stmt->bindValue(':link_fb', $this->__get('link_fb'));
            $stmt->bindValue(':sobre', $this->__get('sobre'));
            $stmt->bindValue(':profissao', $this->__get('profissao'));
            $stmt->bindValue(':localizacao', $this->__get('localizacao'));
            $stmt->execute();

        } else {
            echo "Conta Insttituicional";
        }

    }
    public function editarSenha($newsenha)
    {
        $query = "UPDATE `tb_user` SET `senha`=:newsenha WHERE id=:id AND senha=:senha";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':newsenha', $newsenha);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();
    }
    public function qtdSeguidores()
    {
        $query = "SELECT s.id_seguidor, u.id, u.nome, u.tipo_conta, u.instituicao, u.img FROM `tb_seguir` AS s INNER JOIN tb_user AS u ON (s.id_seguindo = u.id) WHERE s.id_seguidor = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function qtdSeguindo()
    {
        $query = "SELECT u.id, s.id_seguindo, u.nome, u.tipo_conta, u.instituicao, u.img FROM `tb_seguir` AS s INNER JOIN tb_user AS u ON (s.id_seguidor  = u.id) WHERE s.id_seguidor != :id AND s.id_seguindo = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getAllEntidade()
    {
        $query = "SELECT *  FROM tb_instituicional";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getAllEntidadePorNomeInst()
    {
        $query = "SELECT *  FROM tb_instituicional WHERE nome_inst = :nome_inst";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome_inst', $this->__get('nome_inst'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getAllCurso($nome_inst)
    {
        $query = "SELECT cu.id_curso, cu.id_inst, cu.nome_curso, inst.id_admin,inst.id, inst.nome_inst FROM `tb_cursos` AS cu INNER JOIN tb_instituicional AS inst ON( inst.id = cu.id_inst) WHERE inst.nome_inst = :nome_inst";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome_inst', $nome_inst);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function enviarMensagem($receptor, $mensagem)
    {
        $query = "INSERT INTO `tb_mensagem`(`emissor`, `receptor`, `mensagem`) VALUES (:emissor, :receptor, :mensagem)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':emissor', $this->__get('id'));
        $stmt->bindValue(':receptor', $receptor);
        $stmt->bindValue(':mensagem', $mensagem);
        $stmt->execute();
    }
    public function recuperarMensagem($receptor)
    {
        $query = "SELECT * FROM `tb_mensagem` WHERE ( emissor = :emissor AND receptor = :receptor ) OR (receptor =:emissor AND emissor = :receptor) ORDER BY id_mensagem ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':emissor', $this->__get('id'));
        $stmt->bindValue(':receptor', $receptor);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function notificacaoMensagem($receptor)
    {
        $query = "SELECT msg.*, us.nome, us.id, us.img FROM tb_mensagem AS msg INNER JOIN tb_user AS us  ON( msg.emissor= us.id) WHERE ( emissor = :emissor AND receptor = :receptor )  ORDER BY msg.id_mensagem DESC LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':emissor', $this->__get('id'));
        $stmt->bindValue(':receptor', $receptor);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function salvarAssuntoDeInteresse($area_fomrcao, $nivel_academico)
    {
        $query = "INSERT INTO `tb_assuntos_interesse`(`id_user`, `area_fomrcao`, `nivel_academico`) VALUES (:id_user, :area_fomrcao , :nivel_academico)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id'));
        $stmt->bindValue(':area_fomrcao', $area_fomrcao);
        $stmt->bindValue(':nivel_academico', $nivel_academico);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAssuntosUser()
    {
        $query = "SELECT * FROM `tb_assuntos_interesse` WHERE id_user = :id_user";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id'));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getForumPergunta()
    {
        $query = "SELECT p.id_pergunta, p.questao, p.detalhes_questao ,COUNT(r.id_resposta) AS quantidade_respostas, u.nome, u.img, u.id FROM tb_pergunta p LEFT JOIN tb_user u ON p.id_user = u.id LEFT JOIN tb_resposta r ON p.id_pergunta = r.id_pergunta GROUP BY p.id_pergunta, p.questao, u.nome, u.img;";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFiltrarPorPesquisaPergunta($questao)
    {
        $query = "SELECT p.id_pergunta, p.questao, p.detalhes_questao ,COUNT(r.id_resposta) AS quantidade_respostas, u.nome, u.img, u.id FROM tb_pergunta p LEFT JOIN tb_user u ON p.id_user = u.id LEFT JOIN tb_resposta r ON p.id_pergunta = r.id_pergunta
        WHERE questao LIKE :questao
        GROUP BY p.id_pergunta, p.questao, u.nome, u.img;";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':questao', "%" . $questao . "%");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFiltrarPorNivelPergunta($nivel_academico)
    {
        $query = "SELECT p.id_pergunta, p.questao, p.detalhes_questao ,COUNT(r.id_resposta) AS quantidade_respostas, u.nome, u.img, u.id FROM tb_pergunta p LEFT JOIN tb_user u ON p.id_user = u.id LEFT JOIN tb_resposta r ON p.id_pergunta = r.id_pergunta
        WHERE nivel_academico = :nivel_academico
        GROUP BY p.id_pergunta, p.questao, u.nome, u.img;";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nivel_academico', $nivel_academico);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFiltrarPorAreaPergunta($area_formacao)
    {
        $query = "SELECT p.id_pergunta, p.questao, p.detalhes_questao ,COUNT(r.id_resposta) AS quantidade_respostas, u.nome, u.img, u.id FROM tb_pergunta p LEFT JOIN tb_user u ON p.id_user = u.id LEFT JOIN tb_resposta r ON p.id_pergunta = r.id_pergunta
        WHERE area_formacao = :area_formacao
        GROUP BY p.id_pergunta, p.questao, u.nome, u.img;";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':area_formacao', $area_formacao);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOnlyPergunta($id_pergunta)
    {
        $query = "SELECT p.id_pergunta, p.questao, p.detalhes_questao,COUNT(r.id_resposta) AS quantidade_respostas, u.nome, u.img, u.id
        FROM tb_pergunta p
        LEFT JOIN tb_user u ON p.id_user = u.id
        LEFT JOIN tb_resposta r ON p.id_pergunta = r.id_pergunta
        WHERE p.id_pergunta = :id_pergunta
        GROUP BY p.id_pergunta, p.questao, u.nome, u.img;";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pergunta', $id_pergunta);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    //
    function fazerPerguntaForum($area_fomrcao, $nivel_academico, $detalhes_questao, $questao)
    {
        //INSERT INTO `tb_pergunta`(`id_user`, `questao`, `detalhes_questao`, `area_formacao`, `nivel_academico`) VALUES () 
        $query = "INSERT INTO `tb_pergunta`(`id_user`, `questao`, `detalhes_questao`, `area_formacao`, `nivel_academico`) VALUES (:id_user, :questao, :detalhes_questao , :area_formacao , :nivel_academico)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id'));
        $stmt->bindValue(':questao', $questao);
        $stmt->bindValue(':detalhes_questao', $detalhes_questao);
        $stmt->bindValue(':area_formacao', $area_fomrcao);
        $stmt->bindValue(':nivel_academico', $nivel_academico);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function respostaPerguntaForum($resposta, $id_pergunta)
    {
        //INSERT INTO `tb_pergunta`(`id_user`, `questao`, `detalhes_questao`, `area_formacao`, `nivel_academico`) VALUES () 
        $query = "INSERT INTO `tb_resposta`( `id_user`, `resposta`, `id_pergunta`) VALUES (:id_user, :resposta, :id_pergunta)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id'));
        $stmt->bindValue(':resposta', $resposta);
        $stmt->bindValue(':id_pergunta', $id_pergunta);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //Todas as Respostas de Uma Pergunta
    public function getAllRespotaOfOnlyPergunta($id_pergunta)
    {
        $query = "SELECT r.resposta, r.id_resposta, r.id_pergunta, COUNT(r.id_resposta) AS quantidade_respostas, u.nome, u.img, u.id
        FROM tb_pergunta p
        LEFT JOIN tb_resposta r ON p.id_pergunta = r.id_pergunta
        LEFT JOIN tb_user u ON r.id_user = u.id
        WHERE p.id_pergunta = :id_pergunta
        GROUP BY r.id_resposta, u.nome, u.img;";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_pergunta', $id_pergunta);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //Chat
    public function getAllMsgOfEmssior($emissor)
    {
        $query = "SELECT msg.emissor, msg.receptor, msg.mensagem
        FROM tb_mensagem AS msg
        WHERE (msg.receptor, msg.id_mensagem) IN (
            SELECT receptor, MAX(id_mensagem)
            FROM tb_mensagem
            WHERE emissor = :emissor
            GROUP BY receptor 
        )
        ORDER BY msg.id_mensagem DESC;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':emissor', $emissor);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
?>