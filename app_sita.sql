-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Ago-2022 às 13:52
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `app_sita`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_projeto`
--

CREATE TABLE `tb_projeto` (
  `id_projeto` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nome_projeto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `curso` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `area_formacao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nome_instituicao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data_registro` date NOT NULL DEFAULT current_timestamp(),
  `upload` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tb_projeto`
--

INSERT INTO `tb_projeto` (`id_projeto`, `id_user`, `nome_projeto`, `curso`, `area_formacao`, `nome_instituicao`, `data_registro`, `upload`) VALUES
(1, 3, 'Joel Tete', 'Eng. Informatica', 'Exatas', '', '2022-08-06', '1-Introdução à Inteligência.pdf'),
(2, 3, 'IA Exercício', 'Eng. Informatica', 'Exatas', '', '2022-08-06', 'IA-Exercicio.pdf'),
(5, 4, 'Projeto Final do Cinfotec', 'Eng. Informatica', 'Exatas', '', '2022-08-06', '02 - Guia para Aula Prática - Introdução a Prolog.pdf'),
(6, 4, 'TCC de Quimica', 'Eng. Química', 'Exatas', '', '2022-08-06', '03 - Aula Prática 1 - Introdução a Prolog - Copia.pdf'),
(7, 4, 'Diagrama de Classe UML', 'Eng. Software', 'Exatas', '', '2022-08-06', 'O que é um diagrama de classe UML_ _ Lucidchart.pdf'),
(8, 3, 'Php com MySql e PDO', 'Eng. Informatica', 'Exatas', '', '2022-08-06', 'Lista de App_Web para fazer.pdf'),
(9, 3, 'Sistema de Cabeamento Televisivo', 'Eng. Telecomunicação', 'Exatas', '', '2022-08-06', 'Regulamento PAP Curso de Qualificação IH TIC CINFOTEC RAngel.pdf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_seguir`
--

CREATE TABLE `tb_seguir` (
  `id` int(11) NOT NULL,
  `id_seguidor` int(11) NOT NULL,
  `id_seguindo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(32) NOT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comentario` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `senha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tb_user`
--

INSERT INTO `tb_user` (`id`, `nome`, `email`, `img`, `telefone`, `comentario`, `likes`, `senha`) VALUES
(3, 'Joel Malamba', 'joeybraen45@gmail.com', 'IMG-62ec9edc0b39f3.94195896.jpg', NULL, NULL, NULL, 1234),
(4, 'África Malamba', 'africa@gmail.com', 'IMG-62ec9f1d6fd573.26570792.jpg', NULL, NULL, NULL, 1234),
(5, 'Joel Tste Tste', 'joeybraen453@gmail.com', 'IMG-62ec9f1d6fd573.26570792.jpg', NULL, NULL, NULL, 1234);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_projeto`
--
ALTER TABLE `tb_projeto`
  ADD PRIMARY KEY (`id_projeto`),
  ADD KEY `id_user` (`id_user`);

--
-- Índices para tabela `tb_seguir`
--
ALTER TABLE `tb_seguir`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_projeto`
--
ALTER TABLE `tb_projeto`
  MODIFY `id_projeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tb_seguir`
--
ALTER TABLE `tb_seguir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_projeto`
--
ALTER TABLE `tb_projeto`
  ADD CONSTRAINT `tb_projeto_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
