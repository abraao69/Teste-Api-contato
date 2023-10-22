-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: testinho-mariadb:3306
-- Tempo de geração: 22/10/2023 às 23:01
-- Versão do servidor: 11.1.2-MariaDB-1:11.1.2+maria~ubu2204
-- Versão do PHP: 8.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `api`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `Contato`
--

CREATE TABLE `Contato` (
  `id` int(11) NOT NULL,
  `nome` varchar(128) NOT NULL,
  `sobrenome` varchar(128) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `email` varchar(128) NOT NULL,
  `empresa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `Contato`
--

INSERT INTO `Contato` (`id`, `nome`, `sobrenome`, `data_nascimento`, `telefone`, `celular`, `email`, `empresa_id`) VALUES
(32, 'Thiago', 'martins', '1790-01-01', '1334576543210', '8334567890', 'novonome@example.com', 95),
(33, 'Pedro', 'Ribeiro', '1989-08-25', '7777777777', '8888888888', 'pedro.ribeiro@example.com', 96),
(34, 'Ana', 'Ferreira', '1995-12-10', '1111111111', '2222222222', 'ana.ferreira@example.com', 97),
(35, 'Carlos', 'Martins', '1978-03-30', '5555555555', '6666666666', 'carlos.martins@example.com', 98),
(36, 'Maria', 'Pereira', '1990-05-15', '9876543210', '1234567890', 'maria.pereira@example.com', 99),
(37, 'João', 'Silva', '1985-10-20', '1234567890', '9876543210', 'joao.silva@example.com', 100),
(44, 'João', 'Silva', '1985-10-20', '1234567890', '9876543210', 'joao.silva@example.com', 107);

-- --------------------------------------------------------

--
-- Estrutura para tabela `Empresa`
--

CREATE TABLE `Empresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `Empresa`
--

INSERT INTO `Empresa` (`id`, `nome`) VALUES
(95, 'Casas pedro'),
(96, 'Microsoft'),
(97, 'Amazon'),
(98, 'Apple'),
(99, 'Coca-Cola'),
(100, 'PontoFrio'),
(107, 'PontoFrio');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `Contato`
--
ALTER TABLE `Contato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `Empresa`
--
ALTER TABLE `Empresa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `Contato`
--
ALTER TABLE `Contato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `Empresa`
--
ALTER TABLE `Empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `Contato`
--
ALTER TABLE `Contato`
  ADD CONSTRAINT `Contato_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `Empresa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
