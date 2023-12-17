<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class MsgController extends Action
{

    public function index()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $user = Container::getModel('Usuario');
            $user->__set('id', $_GET['id']);

            $this->view->user = $user->visitarPerfil();

            $receptores = $user->getAllMsgOfEmssior($_SESSION['id']);

            $user_receptor = [];
            foreach ($receptores as $key => $value) {

                $user_receptor[$key]['emissor'] = $value['emissor'];
                $user_receptor[$key]['receptor'] = $value['receptor'];
                $user_receptor[$key]['mensagem'] = $value['mensagem'];

                $user->__set('id', $value['receptor']);

                $user_query = $user->visitarPerfil()[0];
                $user_receptor[$key]['nome'] = $user_query['nome'];
                $user_receptor[$key]['img'] = $user_query['img'];
            }

            $this->view->receptor_user = $user_receptor;
            $this->render('index', 'layout_msg');
        } else {
            header("Location: /login?login=erro1");
        }
    }

    public function teste()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $user = Container::getModel('Usuario');
            $user->__set('id', $_GET['id']);

            $this->view->user = $user->visitarPerfil();
            print_r($this->view->user);
            $this->render('index', "layout_chat");
        } else {
            header("Location: /login?login=erro1");
        }
    }
    public function enviarMsg()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            print_r($_POST);

            $user = Container::getModel('Usuario');
            $user->__set('id', $_POST['emissor']);

            $user->enviarMensagem($_POST["receptor"], $_POST['mesagem']);

        } else {
            header("Location: /login?login=erro1");
        }
    }

    public function recuperarMsg()
    {
        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $user = Container::getModel('Usuario');
            $user->__set('id', $_SESSION['id']);

            $row_msg = $user->recuperarMensagem($_GET["id_receptor"]);

            foreach ($row_msg as $msg) {

                if ($msg["receptor"] == $_GET["id_receptor"]) {
                    echo ' <div class="chat-message-right pb-4">';
                    echo '<div>
                        <img src="/app/assets/img/' . $_SESSION['img'] . '"
                            class="rounded-circle mr-1" alt="' . $_SESSION["nome"] . '" width="40" height="40">
                        <div class="text-muted small text-nowrap mt-2">2:37 am</div>
                    </div>';
                    echo ' <div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
                        <div class="font-weight-bold mb-1"> Eu</div>
                        ' . $msg["mensagem"] . '
                    </div>';
                    echo '</div>';
                } else {
                    $user->__set('id', $msg["emissor"]);
                    $emissor = $user->visitarPerfil()[0];

                    echo ' <div class="chat-message-left pb-4">';
                    echo '<div>
                        <img src="/app/assets/img/' . $emissor['img'] . '"
                            class="rounded-circle mr-1" alt="' . $emissor["nome"] . '" width="40" height="40">
                        <div class="text-muted small text-nowrap mt-2">2:37 am</div>
                    </div>';
                    echo ' <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
                        <div class="font-weight-bold mb-1">' . $emissor["nome"] . '</div>
                        ' . $msg["mensagem"] . '
                    </div>';
                    echo '</div>';
                }


            }


        } else {
            header("Location: /login?login=erro1");
        }
    }


}


?>