<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;


class IndexController extends Action
{

	public function index()
	{

		$upload = Container::getModel('Projeto');
		$user = Container::getModel('Usuario');
		$mentor = Container::getModel('Mentor');

		//Todos os Usuarios do Sistema
		$this->view->user = $user->getAllUser();

		//Lista de Todos os Mentores
		$this->view->mentores = $mentor->getAllMentores();

		//Lista de Instituições
		$this->view->inst = $user->getAllEntidade();

		//Lista de Todos Projetos Disponiveis
		$this->view->upload = $upload->getAllProjet();


		$this->render('index');

	}

	//Link para Registrar
	public function registrar()
	{
		$this->view->cadastroErroCampos = false;
		$this->view->cadastroErroEmail = false;
		$this->render('registrar');
	}
	public function login()
	{

		$this->render('login');
	}

	//Criar Conta 
	public function criaConta()
	{
		for ($i = 0; $i < 30000000; $i++) {
			# code...
		}
		//Receber os dados do formulário
		$user = Container::getModel('Usuario');

		$user->__set('nome', $_POST['nome']);
		$user->__set('email', $_POST['email']);
		$user->__set('senha', $_POST['senha']);
		$user->__set('img', $_FILES['image']);
		$user->__set('tipo_conta', $_POST['tipo_conta']);


		$this->view->user = array(
			'nome' => $_POST['nome'],
			'email' => $_POST['email'],
			'senha' => $_POST['senha']
		);

		if ($user->validarCadastro() && count($user->getUserPorEmail()) == 0) {
			$user->salvar();
			echo "/login?q=Faça o login";

		} elseif (count($user->getUserPorEmail()) > 0) {
			echo "*Erro, Email já foi usado!";

		} else {
			echo "*Erro, Preencher todos os caompos!";

		}
	}
}


?>