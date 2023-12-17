<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class AppInstController extends Action
{

    public function home()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $upload = Container::getModel('Projeto');
            $conta = Container::getModel('ContaInst');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);

            $conta->__set('id', $_SESSION['id']);
            $upload->__set('id_user', $_SESSION['id']);

            $_SESSION['id_inst'] = count($conta->getAllCursoPorIdAdminInst()) > 0 ? $conta->getAllCursoPorIdAdminInst()[0]['id_inst'] : "";

            $conta->__set('id_inst', $_SESSION['id_inst']);
            $this->view->solicitacao = $conta->getSolicaitacaoPorInts();
            $this->view->user = array();
            $this->view->upload = $upload->getAllProjetUser();
            //Este variavel recebe os cursos cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->cursos = $conta->getAllCursoPorIdAdminInst();
            //Este variavel recebe os faculdades cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->universidade = $conta->getFaculdadePorIdInst();


            $this->view->qtdProjeto = count($conta->getSolicaitacaoAceitas()) + count($this->view->upload);
            $this->view->qtdSeguidores = count($conta->qtdSeguindo());
            $this->view->qtdSolicitacaoAceitas = count($conta->getSolicaitacaoAceitas());


            $this->render('home', "layout_inst");
        } else {
            header("Location: /login?login=erro1");
        }

    }

    public function instPerfil()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $conta->__set('id', $_SESSION['id']);
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            //Este variavel recebe os cursos cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->cursos = $conta->getAllCursoPorIdAdminInst();
            //Este variavel recebe os faculdades cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->universidade = $conta->getFaculdadePorIdInst();
            $this->view->user = count($conta->getAllInstPorIdAdmin()) > 0 ? $conta->getAllInstPorIdAdmin()[0] : [];

            $this->render('inst-profile', "layout_inst");
        } else {
            header("Location: /login?login=erro1");
        }


    }

    public function configurarContaInst()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            //Model ContaInst
            $conta = Container::getModel('ContaInst');


            //Receber os dados do formulário para ContaInst
            $conta->__set('entidade', $_POST['entidade']);
            $conta->__set('nome_inst', $_POST['nome_inst']);
            $conta->__set('id', $_SESSION['id']);



            if (count($conta->getInstPorIdAdmin()) == 0) {
                $conta->configurarContaInst();
                $conta->__set('id_inst', $conta->getInstPorIdAdmin()[0]['id']);

                if ($_POST['entidade'] == "Universidade") {

                    for ($i = 0; $i < count($_POST['faculdade']); $i++) {
                        $conta->__set('faculdade', $_POST['faculdade'][$i]);
                        $conta->inserirFaculdade();
                    }

                } else {
                    for ($i = 0; $i < count($_POST['curso']); $i++) {
                        $conta->__set('curso', $_POST['curso'][$i]);
                        $conta->inserirCurso();
                    }

                }
                header("Location: /editarInst");

            }
        } else {
            header("Location: /login?login=erro1");
        }
    }
    public function inserirCurso()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            //Model ContaInst
            $conta = Container::getModel('ContaInst');

            //Receber os dados do formulário para ContaInst
            $conta->__set('id', $_SESSION['id']);
            $conta->__set('id_inst', $_SESSION['id_inst']);

            $conta->__set('curso', $_POST['curso']);
            $conta->inserirCurso($_POST['id_faculdade']);


        } else {
            header("Location: /login?login=erro1");
        }
    }
    public function salvoInst()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $conta->__set('id', $_SESSION['id']);

            $this->view->upload = [];

            $this->view->seguindo = $conta->qtdSeguindo();
            $this->view->seguidores = $conta->qtdSeguidores();

            //Este variavel recebe os cursos cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->cursos = $conta->getAllCursoPorIdAdminInst();

            //Este variavel recebe os faculdades cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->universidade = $conta->getFaculdadePorIdInst();
            $this->view->user = [];

            ///$_SESSION['id_inst'] = count($this->view->universidade) > 0 ? $this->view->universidade[0]['id_inst']:$this->view->cursos[0]['id_inst'];

            $this->render('salvo', "layout_inst");


        } else {
            header("Location: /login?login=erro1");
        }

    }
    public function getValorListOrGrade()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $conta->__set('id', $_SESSION['id']);

            //Este variavel recebe os cursos cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->cursos = $conta->getAllCursoPorIdAdminInst();

            //Este variavel recebe os faculdades cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
            $this->view->universidade = $conta->getFaculdadePorIdInst();

            $valorListOrGrade = count($this->view->universidade) > 0 ? $this->view->universidade[0]['ative_list_grade'] : $this->view->cursos[0]['ative_list_grade'];

            echo $valorListOrGrade;


        } else {
            header("Location: /login?login=erro1");
        }

    }
    public function getCursoPorNomeFaculdade()
    {
        session_start();
        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            //echo "Aqui Teste";
            $conta = Container::getModel('ContaInst');
            $conta->__set('id', isset($_GET['id_faculdade']) ? $_GET['id_faculdade'] : $_SESSION['id']);

            $this->view->upload = [];

            $this->view->seguindo = $conta->qtdSeguindo();
            $this->view->seguidores = $conta->qtdSeguidores();

            //Este variavel recebe os cursos cadastradas de uma faculdade( Tbm usada para listar no "layout_inst.phtml" na componente "Árae de Formação" )
            $this->view->cursos = $conta->getAllCursoPorIdFaculdade();

            echo "<nav style='--bs-breadcrumb-divider:" . '>' . "; margin-top:10px'>";
            echo "<ol class='breadcrumb'>";
            echo "<li class='breadcrumb-item'><a href='/salvoInst'>Voltar</a></li></ol> </nav>";


            if (count($this->view->cursos) == 0 && isset($_GET['id_faculdade'])) {
                echo '<div class="col-md-3">';
                echo "<p>Sem cursos</p>";
                echo '</div>';

            } elseif (count($this->view->cursos) > 0 && isset($_GET['id_faculdade'])) {

                echo '<div class="row folder-grade ' . ($_GET["ative"] == "grade" ? "" : "d-none") . '">';
                foreach ($this->view->cursos as $curso) {
                    echo '<div class="col-6 col-md-3">';
                    echo '<a href="/listarProjetosPorCurso?curso=' . $curso['nome_curso'] . '" class="text-dark d-block"">';
                    echo '<div class="d-flex flex-column">';
                    echo '<i class="bi bi-folder-fill" style=" font-size: 100px;"></i>';
                    echo '<small style="font-size:10px;"><strong>' . strtoupper($curso['nome_curso']) . '</strong></small>';
                    echo '</div>
                              </a>
                           </div>';
                }
            }
            echo ' <div id="folder_plus_teste" class="icon col-md-3 mt-4" style="cursor: pointer" onclick="addPastaAEntidade(' . "'/inserirCurso'" . ',' . $_GET['id_faculdade'] . ' )">
                        <i class="bi bi-folder-plus" style=" font-size: 80px;"></i>
                        <div class="label">folder-plus</div>
                      </div>
            </div>';
            echo '<div class="folder-list ' . ($_GET["ative"] == "list" ? "" : "d-none") . '">';
            echo '<ul class="list-group">';
            foreach ($this->view->cursos as $curso) {
                echo '<li class="list-group-item" style="font-size: 25px;cursor: pointer"><i class="bi bi-folder-fill"></i>' . $curso['nome_curso'] . '</li>';

            }
            echo '<li class="list-group-item" style="font-size: 25px;cursor: pointer"><i class="bi bi-folder-plus"></i> Mais Pastas</li>';
            echo '</ul>';
            echo "</div>";

        } else {
            header("Location: /login?login=erro1");
        }
    }

    public function getCursoPorNomeCurso()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);

            $conta->__set('id', isset($_GET['id_admin']) ? $_GET['id_admin'] : $_SESSION['id']);
            $conta->__set('curso', $_GET['curso']);
            $conta->__set('nome_inst', $conta->getAllCursoPorIdAdminInst()[0]['nome_inst']);


            $this->view->upload = [];
            $this->view->seguindo = $conta->qtdSeguindo();
            array_push($this->view->upload, $conta->getCursoPorNomeCurso(), $conta->getProjetoPorCursoInst());
            $this->view->seguidores = $conta->qtdSeguidores();

            if (isset($_GET['id_admin'])) {
                $this->view->user = $conta->getAllInstPorIdAdmin();

                if ($_SESSION['tipo_conta'] == "Conta Comum") {
                    $user = Container::getModel('Usuario');
                    $user->__set('id', $_SESSION['id']);
                    $this->view->assunto = $user->getAssuntosUser();
                    $this->render('perfil', "layout_user");
                } else {
                    $this->render('perfil', "layout_inst");
                }

            } else {
                //Este variavel recebe os cursos cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
                $this->view->cursos = $conta->getAllCursoPorIdAdminInst();
                //Este variavel recebe os faculdades cadastradas, para listar no "layout_inst.phtml" na componente "Árae de Formação"
                $this->view->universidade = $conta->getFaculdadePorIdInst();

                $this->render('salvo', "layout_inst");
            }

        } else {
            header("Location: /login?login=erro1");
        }

    }

    public function getSolicaitacaoPorInts()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $conta->__set('id', $_SESSION['id']);


        } else {
            header("Location: /login?login=erro1");
        }
    }

    public function aprovarSolicitacao()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $conta->__set('aprovar_projeto', $_GET['aprovacao']);

            $conta->aprovarSolicitacao($_GET['id_projeto']);

            header("Location: /homeInst?aprovado=sim");

        } else {
            header("Location: /login?login=erro1");
        }
    }

    public function perfilInst()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $mentor = Container::getModel('Mentor');
            $this->view->mentor = $mentor->getMentorPorIdUser($_SESSION['id']);
            $conta->__set('id', $_GET['id']);

            $this->view->upload = [];
            $this->view->seguindo = $conta->qtdSeguindo();
            $this->view->seguidores = $conta->qtdSeguidores();
            $this->view->user = $conta->getAllInstPorIdAdmin();
            $this->view->cursos = $conta->getAllCursoPorIdAdminInst();

            if ($_SESSION['tipo_conta'] == "Conta Comum") {
                $user = Container::getModel('Usuario');
                $user->__set('id', $_SESSION['id']);
                $this->view->assunto = $user->getAssuntosUser();
                $this->render('perfil', "layout_user");
            } else {
                $this->render('perfil', "layout_inst");
            }

        } else {
            header("Location: /login?login=erro1");
        }
    }
    public function inserirFaculdade()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');

            $conta->__set('id', $_SESSION['id']);
            $conta->__set('id_inst', $_SESSION['id_inst']);
            $conta->__set('faculdade', $_POST['faculdade']);

            $conta->inserirFaculdade();

            $this->view->universidade = $conta->getFaculdadePorIdInst();

            foreach ($this->view->universidade as $faculdade) {
                echo '<div class="col-md-3">';
                echo '<a href="#" class="text-dark d-block" onclick="listarCursos(' . $faculdade["id_faculdade"] . ',' . "'" . $faculdade["nome_faculdade"] . "'" . ')">';
                echo '<div class="d-flex flex-column">';
                echo '<i class="bi bi-folder-fill" style=" font-size: 100px;"></i>';
                echo '<small style="font-size:10px;"><strong>' . strtoupper($faculdade['nome_faculdade']) . '</strong></small>';
                echo '</div>
                         </a>
                      </div>';
            }
            echo '
                   <div class="icon col-md-3 mt-2" style="cursor: pointer" onclick="addPastaAEntidade(' . "'" . '/inserirFaculdade' . "'" . ')">
                     <i class="bi bi-folder-plus" style=" font-size: 80px;"></i>
                     <div class="label">folder-plus</div>
                   </div>';

        } else {
            header("Location: /login?login=erro1");
        }
    }

    public function addListOrGrade()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $conta = Container::getModel('ContaInst');
            $conta->__set('id', $_SESSION['id']);

            $conta->atualizarAtiveListGrade($_GET["list_grade"]);
        } else {
            header("Location: /login?login=erro1");
        }
    }

}
?>