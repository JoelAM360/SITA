<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class AuthController extends Action {

    public  function autenticar()
    {  
      for ($i=0; $i <50000000 ; $i++) { 
         # code...
      }       
       $user = Container::getModel('Usuario');

       $user->__set('email', $_POST['email']);
       $user->__set('senha', $_POST['senha']);

      
       if ( count($user->autenticar()) ) {

         $result_user = $user->autenticar()[0];
         $user->__set('nome', $result_user['nome']);
         $user->__set('id', $result_user['id']);
         $user->__set('img',$result_user['img']);
         $user->__set('tipo_conta',$result_user['tipo_conta']);
  
      
        if ( !empty($result_user['id']) && !empty($result_user['nome']) )  {
             session_start();
  
             $_SESSION['id'] = $user->__get('id');
             $_SESSION['nome'] = $result_user['nome'];
             $_SESSION['img'] = $result_user['img'];
             $_SESSION['tipo_conta'] = $result_user['tipo_conta'];
          
             if ($_SESSION['tipo_conta'] == "Conta Comum") {
                echo '/home';
             } else {
                echo '/homeInst';
             }
         }
             
       } else {
           echo "/login?login=erro";
       }
       
    }
    public function sair()
    {
        session_start();

        session_destroy();
        header("Location: /login"); 
    }

}
?>