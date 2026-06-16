-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Jun-2026 às 18:23
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `atendelab`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `atendimentos`
--

CREATE TABLE `atendimentos` (
  `id` int(11) NOT NULL,
  `pessoa_id` int(11) DEFAULT NULL,
  `tipo_atendimento` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `data_atendimento` date DEFAULT NULL,
  `hora_atendimento` time DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `status` enum('aberto','em_andamento','concluido') NOT NULL DEFAULT 'aberto',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `atendimentos`
--

INSERT INTO `atendimentos` (`id`, `pessoa_id`, `tipo_atendimento`, `usuario_id`, `data_atendimento`, `hora_atendimento`, `descricao`, `observacao`, `status`, `criado_em`, `atualizado_em`) VALUES
(1, 1, 1, 1, '2026-06-12', '00:00:00', '', '', 'em_andamento', '2026-06-12 18:47:14', '2026-06-16 12:39:47'),
(3, 1, 1, 1, '2026-06-12', '00:00:00', 'descricao bala', 'eu observo', 'concluido', '2026-06-12 18:48:57', '2026-06-16 16:08:50'),
(4, 1, 1, 1, '2026-06-14', '14:15:00', 'Nova descricao', 'Nao observo', 'aberto', '2026-06-12 18:49:14', '2026-06-15 16:03:36'),
(7, 1, 1, 1, '2026-06-18', '14:15:00', 'Nova descricao', 'Observacao observada', 'aberto', '2026-06-16 12:43:51', '2026-06-16 13:47:52'),
(11, 5, 1, 6, '2026-06-12', '09:15:00', 'descricao bala', 'observo de fato', 'aberto', '2026-06-16 13:49:46', '2026-06-16 13:49:46');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `periodo` varchar(100) NOT NULL,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo',
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `pessoas`
--

INSERT INTO `pessoas` (`id`, `nome`, `documento`, `telefone`, `curso`, `periodo`, `status`, `atualizado_em`) VALUES
(1, 'teste', '12345678911', '47999999998', 'novoTeste', 'vespertino', 'ativo', '2026-06-16 12:32:48'),
(3, 'teste2', '123456789', '47999999999', 'teste', 'matutino', 'ativo', '2026-06-15 16:02:50'),
(4, 'Carlos Henrique Souza', '123.123.123-10', '(46)79999-0010', 'Engenharia de Software', '3º', 'ativo', '2026-06-15 16:26:34'),
(5, 'Ana Beatriz Souza', '123.111.123-10', '(46)79909-0010', 'Engenharia de Sistemas', '9º', 'ativo', '2026-06-15 16:26:34');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipos_atendimentos`
--

CREATE TABLE `tipos_atendimentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo',
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipos_atendimentos`
--

INSERT INTO `tipos_atendimentos` (`id`, `nome`, `descricao`, `status`, `atualizado_em`) VALUES
(1, 'novoNome', 'nova descricao de atendimento', 'inativo', '2026-06-15 16:02:50'),
(3, 'atendimento2', 'atendimento que atende', 'ativo', '2026-06-15 16:02:50'),
(4, 'Revisão de avaliação', 'Solicitações de revisão de provas, trabalhos e atividades avaliativas.', 'ativo', '2026-06-15 16:21:51'),
(5, 'Apoio à extensão', 'Orientação relacionadas a projetos de extensão e atividades comunitárias.', 'ativo', '2026-06-15 16:21:51');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','atendente') NOT NULL DEFAULT 'atendente',
  `status` enum('ativo','inativo') NOT NULL DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `status`, `criado_em`, `atualizado_em`) VALUES
(1, 'Administrador', 'admin@atendelab.com', '$2y$10$J9P2kU2BAMZ3TZcuxTsW4e1D/lka8EocYHzvyoOZmCNcWDQz3RuVC', 'admin', 'ativo', '2026-06-02 13:57:58', '2026-06-16 12:38:31'),
(2, 'userAtualizado', 'userAtualizado@gmail.com', '$2y$10$S.ajWIg6a53hic2LJXwXLOylRA0BvFaLAG.4P6Shc2owCYZ4on/e6', 'atendente', 'ativo', '2026-06-11 13:23:54', '2026-06-15 16:02:50'),
(6, 'admininastror', 'admininastror@gmail.com', '$2y$10$ZXeep2eoJ9EKbFjzl2Ndzux/LUeZc/wbZlVeQYfzCPz3tPxv4BpIO', 'admin', 'ativo', '2026-06-11 13:44:24', '2026-06-15 16:02:50'),
(7, 'Matheus', 'Matheus@gmail.com', '$2y$10$tNJtm86BUdxH1ECkNFU2AOCq3MHceI7r0L8vuPYXeys8lWfFJpurG', 'atendente', 'ativo', '2026-06-16 16:09:54', '2026-06-16 16:16:40');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pessoa_id` (`pessoa_id`),
  ADD KEY `fk_tipo_atendimento` (`tipo_atendimento`),
  ADD KEY `fk_usuario_id` (`usuario_id`);

--
-- Índices para tabela `pessoas`
--
ALTER TABLE `pessoas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`);

--
-- Índices para tabela `tipos_atendimentos`
--
ALTER TABLE `tipos_atendimentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tipos_atendimentos`
--
ALTER TABLE `tipos_atendimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD CONSTRAINT `fk_pessoa_id` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoas` (`id`),
  ADD CONSTRAINT `fk_tipo_atendimento` FOREIGN KEY (`tipo_atendimento`) REFERENCES `tipos_atendimentos` (`id`),
  ADD CONSTRAINT `fk_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
