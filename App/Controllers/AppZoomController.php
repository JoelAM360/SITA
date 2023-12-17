<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class AppZoomController extends Action {

    public function criarSala() {
		session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

		    $user = Container::getModel('Usuario');
		    $mentor = Container::getModel('Mentor');
           // $user->__set('id', $_SESSION['id']);
            
            $mentor->getMentorPorIdUser($_SESSION['id']);

            $this->render('index',"layout_zoom");


        } else {
            header("Location: /login?login=erro1");
        }

	}
    
}


?>