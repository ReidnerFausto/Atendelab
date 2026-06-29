-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Jun-2026 às 21:59
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
(1, 1, 1, 1, '2026-06-12', '00:00:00', '', '', 'aberto', '2026-06-12 18:47:14', '2026-06-27 15:53:20'),
(3, 1, 1, 1, '2026-06-12', '00:00:00', 'descricao bala', 'eu observo', 'em_andamento', '2026-06-12 18:48:57', '2026-06-27 15:54:45'),
(4, 1, 1, 1, '2026-06-14', '14:15:00', 'Nova descricao', '', 'concluido', '2026-06-12 18:49:14', '2026-06-27 15:56:34'),
(7, 1, 1, 1, '2026-06-18', '14:15:00', 'Nova descricao', 'Observacao observada', 'aberto', '2026-06-16 12:43:51', '2026-06-16 13:47:52'),
(11, 5, 1, 6, '2026-06-12', '09:15:00', 'descricao bala', 'observo de fato', 'aberto', '2026-06-16 13:49:46', '2026-06-16 13:49:46'),
(13, 6, 4, 6, '2026-06-30', '17:05:00', 'Auxilia na materia', '', 'aberto', '2026-06-27 16:05:30', '2026-06-27 16:05:30');

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
(1, 'Joao Guilherme', '12345678911', '47999999998', 'Matematica', '6°', 'ativo', '2026-06-27 16:01:56'),
(4, 'Carlos Henrique Souza', '123.123.123-10', '(46)79999-0010', 'Engenharia de Software', '3º', 'ativo', '2026-06-15 16:26:34'),
(5, 'Ana Beatriz Souza', '123.111.123-10', '(46)79909-0010', 'Engenharia de Sistemas', '9º', 'ativo', '2026-06-15 16:26:34'),
(6, 'Matheu Henrique', '12112355422', '47999999998', 'Geografia', '7°', 'ativo', '2026-06-27 16:03:33');

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
(1, 'Apoio à extensão', 'Orientação relacionadas a projetos de extensão e atividades comunitárias.', 'ativo', '2026-06-27 16:17:47'),
(4, 'Revisão de avaliação', 'Solicitações de revisão de provas, trabalhos e atividades avaliativas.', 'ativo', '2026-06-15 16:21:51'),
(6, 'Simulados', 'Cria sumulados de determinada materia.', 'ativo', '2026-06-27 16:18:18');

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
(6, 'admininastror', 'admininastror@gmail.com', '$2y$10$ZXeep2eoJ9EKbFjzl2Ndzux/LUeZc/wbZlVeQYfzCPz3tPxv4BpIO', 'admin', 'ativo', '2026-06-11 13:44:24', '2026-06-15 16:02:50');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tipos_atendimentos`
--
ALTER TABLE `tipos_atendimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
