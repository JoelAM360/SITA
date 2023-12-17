<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

	protected function initRoutes()
	{
		//Rotas Index
		$routes['index'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'indexController',
			'action' => 'registrar'
		);

		$routes['login'] = array(
			'route' => '/login',
			'controller' => 'indexController',
			'action' => 'login'
		);
		$routes['cadastrar'] = array(
			'route' => '/cadastrar',
			'controller' => 'indexController',
			'action' => 'criaConta'
		);

		//Rotas Auth
		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar'
		);
		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);

		//Rotas AppController
		$routes['set_perfil'] = array(
			'route' => '/set_perfil',
			'controller' => 'AppController',
			'action' => 'userPerfil'
		);
		$routes['home'] = array(
			'route' => '/home',
			'controller' => 'AppController',
			'action' => 'home'
		);

		$routes['forum_pergunta'] = array(
			'route' => '/forum',
			'controller' => 'AppController',
			'action' => 'forumPergunta'
		);

		$routes['forum_resposta'] = array(
			'route' => '/forumrespostas',
			'controller' => 'AppController',
			'action' => 'forumRespostas'
		);

		$routes['fazerpergunta'] = array(
			'route' => '/fazerpergunta',
			'controller' => 'AppController',
			'action' => 'fazerPerguntaForum'
		);

		$routes['pesquisaForum'] = array(
			'route' => '/pesquisaForum',
			'controller' => 'AppController',
			'action' => 'forumPergunta'
		);


		$routes['responderpergunta'] = array(
			'route' => '/responderpergunta',
			'controller' => 'AppController',
			'action' => 'respostaPerguntaForum'
		);

		$routes['solicitarServico'] = array(
			'route' => '/solicitarServico',
			'controller' => 'AppController',
			'action' => 'solicitarServico'
		);


		$routes['app_zoom'] = array(
			'route' => '/criar_sala',
			'controller' => 'AppZoomController',
			'action' => 'criarSala'
		);

		$routes['cadastrarMentor'] = array(
			'route' => '/cadastrarMentor',
			'controller' => 'AppController',
			'action' => 'cadastrarMentor'
		);

		$routes['upload'] = array(
			'route' => '/upload',
			'controller' => 'AppController',
			'action' => 'uploadFiles'
		);
		$routes['salvo'] = array(
			'route' => '/salvo',
			'controller' => 'AppController',
			'action' => 'salvo'
		);
		$routes['pesquisar'] = array(
			'route' => '/pesquisar',
			'controller' => 'AppController',
			'action' => 'pesquisar'
		);
		$routes['perfil'] = array(
			'route' => '/perfil',
			'controller' => 'AppController',
			'action' => 'perfil'
		);
		$routes['seguir'] = array(
			'route' => '/seguir',
			'controller' => 'AppController',
			'action' => 'seguir'
		);
		$routes['editar'] = array(
			'route' => '/editar',
			'controller' => 'AppController',
			'action' => 'editarConta'
		);
		$routes['editarSenha'] = array(
			'route' => '/editarSenha',
			'controller' => 'AppController',
			'action' => 'editarSenha'
		);
		$routes['curtir'] = array(
			'route' => '/curtir',
			'controller' => 'AppController',
			'action' => 'avaliarProjeto'
		);
		$routes['check'] = array(
			'route' => '/check',
			'controller' => 'AppController',
			'action' => 'checkAvailiacao'
		);
		$routes['qtdLikes'] = array(
			'route' => '/qtdLikes',
			'controller' => 'AppController',
			'action' => 'qtdLikesPorProjeto'
		);
		$routes['solicitar'] = array(
			'route' => '/solicitar',
			'controller' => 'AppController',
			'action' => 'solicitarAprovacaoProjeto'
		);
		$routes['getCurso'] = array(
			'route' => '/getCurso',
			'controller' => 'AppController',
			'action' => 'getCursoPorEntidade'
		);
		$routes['excluir'] = array(
			'route' => '/excluir',
			'controller' => 'AppController',
			'action' => 'excluirProjeto'
		);
		$routes['editar_dados'] = array(
			'route' => '/editar_dados',
			'controller' => 'AppController',
			'action' => 'editarDadosProjeto'
		);

		$routes['salvarassunto'] = array(
			'route' => '/salvarassunto',
			'controller' => 'AppController',
			'action' => 'salvarAssuntoDeInteresse'
		);

		$routes['testeq'] = array(
			'route' => '/testeQ',
			'controller' => 'AppController',
			'action' => 'qtdTeste'
		);

		//Rotas AppInstController
		$routes['homeInst'] = array(
			'route' => '/homeInst',
			'controller' => 'AppInstController',
			'action' => 'home'
		);
		$routes['editarInst'] = array(
			'route' => '/editarInst',
			'controller' => 'AppInstController',
			'action' => 'instPerfil'
		);

		$routes['editarContaInst'] = array(
			'route' => '/editarContaInst',
			'controller' => 'AppInstController',
			'action' => 'configurarContaInst'
		);

		$routes['inserirCurso'] = array(
			'route' => '/inserirCurso',
			'controller' => 'AppInstController',
			'action' => 'inserirCurso'
		);

		$routes['salvoInst'] = array(
			'route' => '/salvoInst',
			'controller' => 'AppInstController',
			'action' => 'salvoInst'
		);
		$routes['listarProjetosPorCurso'] = array(
			'route' => '/listarProjetosPorCurso',
			'controller' => 'AppInstController',
			'action' => 'getCursoPorNomeCurso'
		);
		$routes['listarCurso'] = array(
			'route' => '/listarCurso',
			'controller' => 'AppInstController',
			'action' => 'getCursoPorNomeFaculdade'
		);
		$routes['aprovar'] = array(
			'route' => '/aprovar',
			'controller' => 'AppInstController',
			'action' => 'aprovarSolicitacao'
		);
		$routes['perfilInst'] = array(
			'route' => '/perfilInst',
			'controller' => 'AppInstController',
			'action' => 'perfilInst'
		);
		$routes['inserirFaculdade'] = array(
			'route' => '/inserirFaculdade',
			'controller' => 'AppInstController',
			'action' => 'inserirFaculdade'
		);

		$routes['addListOrGrade'] = array(
			'route' => '/addListOrGrade',
			'controller' => 'AppInstController',
			'action' => 'addListOrGrade'
		);

		$routes['getValorListOrGrade'] = array(
			'route' => '/getValorListOrGrade',
			'controller' => 'AppInstController',
			'action' => 'getValorListOrGrade'
		);

		$routes['teste'] = array(
			'route' => '/teste',
			'controller' => 'testeController',
			'action' => 'teste'
		);


		//Rotas Chat SITA
		$routes['enviar'] = array(
			'route' => '/enviarMsg',
			'controller' => 'MsgController',
			'action' => 'enviarMsg'
		);
		$routes['recuperarMsg'] = array(
			'route' => '/recuperarMsg',
			'controller' => 'MsgController',
			'action' => 'recuperarMsg'
		);

		$routes['conversas'] = array(
			'route' => '/conversas',
			'controller' => 'MsgController',
			'action' => 'index'
		);

		$this->setRoutes($routes);
	}

}

?>