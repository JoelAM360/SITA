<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class AppController extends Action
{

    public function home()
    {
        session_start();

        $upload = Container::getModel('Projeto');
        $user = Container::getModel('Usuario');
        $mentor = Container::getModel('Mentor');

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $this->view->user = array();
            $this->view->userSug = $user->sugestoesPerfil();
            $this->view->upload = $upload->getAllProjet();
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $this->view->pagina = "Início";
            $this->view->mentores = $mentor->getAllMentor($_SESSION['id']);
            $this->view->msg = $user->recuperarMensagem($_SESSION['id']);

            $user->__set('id', $_SESSION['id']);
            $this->view->assunto = $user->getAssuntosUser();

        } else {
            $this->view->user = array();
            $this->view->userSug = $user->sugestoesPerfil();
            $this->view->upload = $upload->getAllProjet();
            $this->view->mentor = [];
            $this->view->pagina = "Início";
            $this->view->mentores = $mentor->getAllComJoin();
            $this->view->assunto = [];
        }

        $this->render('home', "layout_user");

    }

    public function getCursoPorEntidade()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('ContaComum');
            $nome_inst = $_GET['nome_curso'];
            echo "<option value=''>Selecinar Curso</option>";
            foreach ($user->getAllCurso($nome_inst) as $value) {
                echo "<option>" . $value["nome_curso"] . "</option>";
            }

            //Receber os dados do formulário
            $user->__set('id_user', $_SESSION['id']);

            //$user->solicitarAprovacaoProjeto();

        } else {
            header("Location: /login?login=erro1");
        }
    }

    public function solicitarAprovacaoProjeto()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('ContaComum');

            //Receber os dados do formulário
            //$id_inst = $user->getAllEntidade()[0]['id'];

            $user->__set('id', $_SESSION['id']);
            $user->__set('id_projeto', $_POST["id_projeto"]);
            $user->__set('nome_inst', $_POST["entidade"]);
            $user->__set('id_inst', $user->getAllEntidadePorNomeInst()[0]['id']);
            $user->__set('n_proc', $_POST["nProc"]);
            //$user->__set('ano', $_POST["nProc"]);
            $user->__set('nome', $_POST["nome"]);
            $user->__set('curso', $_POST["curso"]);
            $user->__set('num_bi', $_POST["bi"]);
            $user->solicitarAprovacaoProjeto();
            header("Location: /salvo?solicitacao=sim");

        } else {
            header("Location: /login?login=erro1");
        }
    }
    //Forum Pergunta e Respostas
    public function forumPergunta()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $upload = Container::getModel('Projeto');
            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $user->__set('id', $_SESSION['id']);
            $this->view->user = array();
            $this->view->userSug = $user->sugestoesPerfil();
            $this->view->upload = $upload->getAllProjet();
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $this->view->pagina = "Início";
            $this->view->mentores = $mentor->getAllMentor($_SESSION['id']);
            $this->view->assunto = $user->getAssuntosUser();

            $this->view->assunto = $user->getAssuntosUser();

            if (isset($_GET['questao'])) {
                $this->view->questao = $user->getFiltrarPorPesquisaPergunta($_GET['questao']);
            } elseif (isset($_GET['nivel_academico'])) {
                $this->view->questao = $user->getFiltrarPorNivelPergunta($_GET['nivel_academico']);
            } elseif (isset($_GET['area_formacao'])) {
                $this->view->questao = $user->getFiltrarPorAreaPergunta($_GET['area_formacao']);
            } else {
                $this->view->questao = $user->getForumPergunta();
            }
            //print_r($this->view->questao);
            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                $this->render('forum_pergunta', "layout_user");

            } else {
                $this->render('forum_pergunta', "layout_inst");

            }

        } else {
            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = [];
            $this->view->pagina = "Início";
            $this->view->assunto = [];

            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                $this->render('forum_pergunta', "layout_user");

            } else {
                $this->render('forum_pergunta', "layout_inst");

            }
        }

    }

    public function forumRespostas()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $upload = Container::getModel('Projeto');
            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $user->__set('id', $_SESSION['id']);
            $this->view->user = array();
            $this->view->userSug = $user->sugestoesPerfil();
            $this->view->upload = $upload->getAllProjet();
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $this->view->pagina = "Início";
            $this->view->mentores = $mentor->getAllMentor($_SESSION['id']);
            $this->view->assunto = $user->getAssuntosUser();
            $this->view->pergunta = $user->getOnlyPergunta($_GET['id_pergunta']);
            $this->view->respostas = $user->getAllRespotaOfOnlyPergunta($_GET['id_pergunta']);
            $this->render('forum_respostas', "layout_user");

        } else {
            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = [];
            $this->view->pagina = "Início";
            $this->view->assunto = [];
            $this->render('forum_respostas', "layout_user");

        }

    }

    //Salvar Uma Questão do Forum: fazerPerguntaForum($area_fomrcao, $nivel_academico, $detalhes_questao, $questao)
    public function fazerPerguntaForum()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Usuario');

            $user->__set('id', $_SESSION['id']);
            $dados = json_decode(file_get_contents('php://input'));
            $pergunta = $user->fazerPerguntaForum($dados->area_formacao, $dados->nivel_academico, $dados->detalhes_questao, $dados->titulo);

            if ($pergunta) {
                echo "Sucesso";
            } else {
                echo "Erro";
            }

        } else {
            header("Location: /login?login=erro1");
        }

    }


    public function respostaPerguntaForum()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Usuario');

            $user->__set('id', $_SESSION['id']);
            $dados = json_decode(file_get_contents('php://input'));
            $pergunta = $user->respostaPerguntaForum($dados->resposta, $dados->id_pergunta);

            if ($pergunta) {
                echo "Sucesso";
            } else {
                echo "Erro";
            }

        } else {
            header("Location: /login?login=erro1");
        }

    }

    // respostaPerguntaForum($resposta, $id_pergunta)
    //Tela de Configuração
    public function userPerfil()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $user->__set('id', $_SESSION['id']);
            $this->view->user = $user->visitarPerfil();
            $this->view->pagina = "Editar Perfil";
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $this->view->assunto = $user->getAssuntosUser();
            $this->view->entidade = $user->getAllEntidade();

            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                $this->render('users-profile', "layout_user");

            } else {
                header("Location: /editarInst");

            }
        } else {
            header("Location: /login?login=erro1");
        }

    }

    public function editarConta()
    {

        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {


            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);

            //Receber os dados do formulário
            $user->__set('id', $_SESSION['id']);
            $user->__set('nome', $_POST['nome']);
            $user->__set('email', $_POST['email']);
            $user->__set('nome_inst', $_POST['instituicao']);
            $user->__set('curso', $_POST['curso']);
            $user->__set('telefone', $_POST['telefone']);
            $user->__set('link_fb', $_POST['facebook']);
            $user->__set('sobre', $_POST['sobre']);
            $user->__set('profissao', $_POST['profissao']);
            $user->__set('localizacao', $_POST['endereco']);




            print_r($this->view->entidade);
            $user->__set('tipo_conta', $_SESSION['tipo_conta']);
            $user->editarConta(); //Executando a edição do dados
            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                header("Location: /set_perfil");


            } else {
                header("Location: /editarInst");

            }

        } else {
            header("Location: /login?login=erro1");
        }

    }


    public function editarSenha()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            //print_r($_POST);
            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $this->view->pagina = "Editar Perfil";
            //Receber os dados do formulário
            $user->__set('id', $_SESSION['id']);
            if ($_POST["newpassword"] != $_POST["renewpassword"]) {
                echo "Senha e Nova senha diferentes";
            } elseif ($user->visitarPerfil()[0]['senha'] != $_POST['password']) {
                echo "Atual incorreta";
            } else {

                $user->__set('senha', $_POST['password']);
                $user->editarSenha($_POST["newpassword"]);
                if ($_SESSION['tipo_conta'] == "Conta Comum") {
                    header("Location: /set_perfil");

                } else {
                    header("Location: /editarInst");
                }

            }


        } else {
            header("Location: /login?login=erro1");
        }
    }

    //Método para levar ao perfil do Usuario logado e listar seus projetos
    public function salvo()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $upload = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');

            $upload->__set('id', $_SESSION['id']);
            $this->view->upload1 = $upload->perfil();

            $this->view->upload = count($this->view->upload1) > 0 ? $this->view->upload1 : $upload->visitarPerfil();


            $this->view->seguindo = $upload->qtdSeguindo();

            $this->view->seguidores = $upload->qtdSeguidores();
            $this->view->entidade = $upload->getAllEntidade();
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $this->view->mentorAluno = $mentor->getAlunoDeUmMentor($_SESSION['id']);
            $this->view->pagina = "Repositório";

            $this->view->assunto = $upload->getAssuntosUser();

            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                $this->render('salvo', "layout_user");
            } else {
                header("Location: /salvoInst");
            }

        } else {
            header("Location: /login?login=erro1");
        }

    }

    public function perfil()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');
            $user->__set('id', $_GET['id']);



            $this->view->user = count($user->perfil()) > 0 ? $user->perfil() : $user->visitarPerfil();
            $this->view->upload = $user->perfil();
            $this->view->seguindo = $user->qtdSeguindo();
            $this->view->seguidores = $user->qtdSeguidores();
            $this->view->mentor = $mentor->getMentorPorIdUser($_GET['id']);
            $this->view->pagina = "Perfil";

            $this->view->assunto = $user->getAssuntosUser();

            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                $this->render('perfil', "layout_user");

            } else {
                $this->render('perfil', "layout_inst");

            }
        } else {
            header("Location: /login?login=erro1");
        }
    }

    //Submeter Projeto
    public function uploadFiles()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            //$this->carregarProjeto();

            //Receber os dados do formulário
            $upload = Container::getModel('Projeto');
            //echo $_FILES['file'];
            $upload->__set('nome_projeto', $_POST['nome']);
            $upload->__set('curso', $_POST['curso']);
            $upload->__set('area_formacao', $_POST['area_formacao']);
            $upload->__set('id_user', $_SESSION['id']);

            echo $upload->carregarProjeto();

        } else {
            header("Location: /login?login=erro1");
        }
    }

    //Excluir Dados Do Projeto
    public function excluirProjeto()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $user = Container::getModel('Projeto');

            $user->__set('id_user', $_SESSION['id']);
            $user->__set('id_projeto', $_GET['id_projeto']);
            $user->excluirProjeto();

            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                header("Location: /salvo?removido=true");

                $destination_path = getcwd() . DIRECTORY_SEPARATOR;
                unlink($destination_path . '/app/assets/upload/' . $_GET['nome_projeto']);
            } else {
                header("Location: /salvoInst?removido=true");
            }

        } else {
            header("Location: /login?login=erro1");
        }
    }

    //Editar dados Do Projeto
    public function editarDadosProjeto()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Projeto');

            $user->__set('nome_projeto', $_POST['nome_projeto']);
            $user->__set('id_projeto', $_POST['id_projeto']);

            $user->editarDadosProjeto();

            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                header("Location: /salvo");
            } else {
                header("Location: /listarCurso?curso=" . $_POST['curso']);
            }

        } else {
            header("Location: /login?login=erro1");
        }
    }


    //Método para pesquisar User, Projeto e Instituição
    public function pesquisar()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            if (!empty($_GET["pesquisar"])) {
                //Model Projeto
                $upload = Container::getModel('Projeto');
                $mentor = Container::getModel('Mentor');
                $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
                //Receber os dados do formulário para Projeto
                $upload->__set('pesquisa', $_GET['pesquisar']);
                $upload->__set('id_user', $_SESSION['id']);

                //Model User
                $user = Container::getModel('Usuario');
                //Receber os dados do formulário para User
                $user->__set('nome', $_GET['pesquisar']);
                $user->__set('id', $_SESSION['id']);
                $this->view->pagina = "Início";
                $this->view->mentores = $mentor->getAllMentor($_SESSION['id']);
                $this->view->assunto = $user->getAssuntosUser();

                $this->view->userSug = $user->sugestoesPerfil();
                //Se user estiver pesquisando por outro User
                if (count($user->searchUser()) > 0) {

                    $this->view->user = $user->searchUser();
                    $this->view->upload = array();

                } elseif (count($user->searchInstituicao()) > 0) {

                    $this->view->user = $user->searchInstituicao();
                    $this->view->upload = array();
                    print_r($this->view->user);

                    //$this->render('home',"layout_user");
                } elseif (count($upload->searchProjet()) > 0) {

                    $this->view->user = array();
                    $this->view->upload = $upload->searchProjet();

                    //$this->render('home',"layout_user");
                }
                //Se o user estiver pesquisando por um Projeto
                else {

                    $this->view->user = array();
                    $this->view->upload = $upload->searchProjet();

                }

                if ($_SESSION['tipo_conta'] == "Conta Comum") {
                    $this->render('home', "layout_user");
                } else {
                    $this->render('home', "layout_inst");
                }
            } else {
                header("Location:/home");
            }

        } else {
            if (!empty($_GET["pesquisar"])) {
                //Model Projeto
                $upload = Container::getModel('Projeto');
                $mentor = Container::getModel('Mentor');
                $this->view->mentor = [];
                //Receber os dados do formulário para Projeto
                $upload->__set('pesquisa', $_GET['pesquisar']);
                // $upload->__set('id_user', $_SESSION['id']);

                //Model User
                $user = Container::getModel('Usuario');
                //Receber os dados do formulário para User
                $user->__set('nome', $_GET['pesquisar']);
                //$user->__set('id', $_SESSION['id']);
                $this->view->pagina = "Início";
                $this->view->mentores = $mentor->getAllComJoin();

                $this->view->userSug = $user->sugestoesPerfil();

                //print_r($user->searchUser());
                //Se user estiver pesquisando por outro User
                if (count($user->searchUser()) > 0) {

                    $this->view->user = $user->searchUser();
                    $this->view->upload = array();

                } elseif (count($user->searchInstituicao()) > 0) {
                    $this->view->user = $user->searchInstituicao();
                    $this->view->upload = array();

                } elseif (count($upload->searchProjet()) > 0) {
                    $this->view->user = array();
                    $this->view->upload = $upload->searchProjet();

                }
                //Se o user estiver pesquisando por um Projeto
                else {
                    $this->view->user = array();
                    $this->view->upload = $upload->searchProjet();

                }
                $this->render('home', "layout_user");
            } else {
                header("Location:/home");
            }
        }
    }

    //Salvar os dados do Assunto de Interesse do Usuario
    public function salvarAssuntoDeInteresse()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Usuario');
            $user->__set('id', $_SESSION['id']);

            if ($user->salvarAssuntoDeInteresse($_POST['area_formacao'], $_POST['nivel_academico'])) {
                echo "Sucesso";
            } else {
                echo "Erro";
            }
        } else {
            header("Location: /login?login=erro1");
        }
    }
    public function cadastrarMentor()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');

            // $user->__set('id', $_SESSION['id']);
            $mentor->__set('bi', $_POST['bi']);
            $mentor->__set('tipo_mentoria', $_POST['tipo_mentor']);
            $mentor->__set('habilitacao', $_POST['habilitacao']);

            if (count($mentor->getMentorPorIdUser($_SESSION['id'])) == 0) {
                if (isset($_POST['condicoes']) && $_POST['condicoes'] == 1) {
                    if ($mentor->cadastarMentor($_SESSION['id'])) {
                        header("Location: /salvo?q=Cadatro Feito!");

                    } else {
                        header("Location: /salvo?erro=Alguma coisa correu mal. Tente novamente!");
                    }
                }
            } else {
                header("Location: /salvo?q=Já Existe!");
            }




        } else {
            header("Location: /login?login=erro1");
        }

    }
    //Aciona método para avaliar projeto
    public function avaliarProjeto()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Projeto');

            //Receber os dados do formulário
            $user->__set('id_user', $_SESSION['id']);
            $user->__set('id_projeto', $_GET['id_projeto']);

            $user->avaliarProjeto($_GET['acao']);

        } else {
            header("Location: /login?login=erro1");
        }
    }

    //Verifica se um projeto já foi avaliado
    public function checkAvailiacao()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Projeto');

            //Receber os dados do formulário
            $user->__set('id_user', $_SESSION['id']);

            echo $user->checkAvailiacao();

        } else {
            header("Location: /login?login=erro1");
        }
    }
    //Acina metodo para pegar qtd de Likes de Cada Projeto
    public function qtdLikesPorProjeto()
    {
        session_start();
        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Projeto');
            //Receber os dados do formulário           
            echo $user->qtdLikesPorProjeto($_GET['id_projeto'])[0]["qtdLikes"];

        } else {
            header("Location: /login?login=erro1");
        }
    }

    //Ação para seguir um Usuario
    public function seguir()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Usuario');

            $user->__set('id', $_SESSION['id']);
            if ($user->seguir($_GET['acao'], $_GET['id_seguidor']) == "OK") {
                $_SESSION['id_seguidor'] = $_GET['id_seguidor'];
                $user->upadteQtdSeguidores(count($user->qtdSeguindoPorID($_GET['id_seguidor'])), $_GET['id_seguidor']);

            }
        } else {
            header("Location: /login?login=erro1");
        }
    }
    //Método para levar ao perfil do usuario logado
    public function solicitarServico()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $user = Container::getModel('Usuario');
            $mentor = Container::getModel('Mentor');

            // $user->__set('id', $_SESSION['id']);
            $mentor->__set('id_mentor', $_POST['id_mentor']);
            $mentor->__set('tipo_mentoria', $_POST['tipo_mentor']);

            $pre_projeto = $mentor->uploadFiles('/app/assets/docs/', $_FILES['pre_projeto']);
            $comprovativo = $mentor->uploadFiles('/app/assets/docs/', $_FILES['comprovativo']);

            $verficar = $mentor->solicitarAssociacaoMentoria($_SESSION['id'], $comprovativo, $pre_projeto);

            if ($verficar) {
                header('Location: /perfil?id=' . $_POST['id_user'] . '&q=Sucess');
            } else {
                header('Location: /perfil?id=' . $_POST['id_user'] . '&erro=Tente novamente');
            }
        } else {
            header("Location: /login?login=erro1");
        }

    }

}
?>