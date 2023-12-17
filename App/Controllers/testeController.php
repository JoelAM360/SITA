<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class TesteController extends Action
{

    public function login()
    {

        $this->render('login');
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
                    echo '<div class="message">';
                    echo '<div class="photo" style="background-image: url(public/app/img/' . $_SESSION['img'] . ');">';
                    echo '<div class="online"></div>';
                    echo '</div>';
                    echo '<p class="text">' . $msg["mensagem"] . '</p>';
                    echo '</div>';
                } else {
                    echo '<div class="message text-only">';
                    echo '<div class="response">';
                    echo '<p class="text">' . $msg["mensagem"] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }


            }


        } else {
            header("Location: /login?login=erro1");
        }
    }


}


?>