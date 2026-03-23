-- Adminer 5.4.2 MySQL 8.0.45 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `imoveis`;
CREATE TABLE `imoveis` (
  `inscricao_municipal` int NOT NULL AUTO_INCREMENT,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `cep` varchar(15) DEFAULT NULL,
  `contribuinte_id` int NOT NULL,
  PRIMARY KEY (`inscricao_municipal`),
  KEY `contribuinte_id` (`contribuinte_id`),
  CONSTRAINT `imoveis_ibfk_1` FOREIGN KEY (`contribuinte_id`) REFERENCES `pessoas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `imoveis` (`inscricao_municipal`, `logradouro`, `numero`, `bairro`, `complemento`, `cep`, `contribuinte_id`) VALUES
(3,	'joao becker',	'12334',	'centro',	'casa 1',	'209429492',	4),
(4,	'avenida feitoria',	'12',	'feitoria',	'casa 5',	'22222222',	5);

DROP TABLE IF EXISTS `pessoas`;
CREATE TABLE `pessoas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `sexo` enum('M','F','Outro') NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `pessoas` (`id`, `nome`, `data_nascimento`, `cpf`, `sexo`, `telefone`, `email`) VALUES
(4,	'mario silva',	'9158-03-23',	'20202030330',	'M',	'510329342904390',	'aaaaaaa@gmail.com'),
(5,	'joao pedro',	'1954-07-23',	'11111111',	'M',	'5191285823',	'jaoaopedro@gmail.com');

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`) VALUES
(1,	'Assistente Administrativo',	'admin',	'$2y$12$5jgZuNHfGvecITsEPcOBtuhtEr7GPtQEwNOsG0nWGW1LFeex30GfG'),
(2,	'victor quadros',	'victor',	'$2y$12$0OIbuBVQfo1g9DOn0ViNl.TRcYDzgXuPUWQwY/5JFH.5Fn5WXUqfe'),
(3,	'felipe luis',	'fluis',	'$2y$12$wAncZUi8HsbYXvYhFyDUzeVQbB5YhmjZBI1r1sHSNAKA5zqCBuFJi');

-- 2026-03-23 00:21:38 UTC