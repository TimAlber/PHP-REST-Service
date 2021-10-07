
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci PRIMARY KEY,
  `username` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pw` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `category` (
  `id` varchar(36) PRIMARY KEY,
  `userId` varchar(36) NOT NULL,
  `description` varchar(200) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `data` (
  `id` varchar(36) PRIMARY KEY,
  `categoryId` varchar(36) NOT NULL,
  `logValue` DECIMAL(10,2) NOT NULL,
  `logDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `username`, `email`, `pw`, `created`) VALUES
('0bb28278-d28a-11e7-b93f-2c4d544f8fe0', 'fionafit', 'test@test.de', '$2y$10$IeMpvUuMIrnxFHN.j94tEe.T1rjsTga1yYoyt5JAAXYUwbbjh1km6', '2020-04-10 09:13:20');

INSERT INTO `category` (`id`, `userId`, `description`, `unit`, `created`) VALUES
('6ab28278-91e4-11e7-b93f-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0'
  , 'Gewicht', 'kg', '2020-04-10 09:13:20'),
('7ab28278-91e4-11e7-b93f-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0'
  , 'Kalorien', 'kcl', '2020-04-10 09:13:20'),
('8bb28278-91e4-11e7-b93f-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0'
  , 'Ausgaben', 'EUR', '2020-04-10 09:13:20'),
('9cb28278-91e4-11e7-b93f-2c4d544f8fe0', '0bb28278-d28a-11e7-b93f-2c4d544f8fe0'
  , 'Lernzeit', 'Minuten', '2020-04-10 09:13:20');

INSERT INTO `data` (`id`, `categoryId`, `logValue`, `logDate`) VALUES
('5c86c63e-91e4-4c1c-982e-c3957daf0aca', '6ab28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 45.3, '2020-12-14 06:14:28'),
('5d86c63e-91e4-4c1c-982e-c3957daf0aca', '6ab28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 45.1, '2021-01-07 06:34:28'),
('5e86c63e-91e4-4c1c-982e-c3957daf0aca', '6ab28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 44.9, '2020-11-16 06:42:28'),
('5f86c63e-91e4-4c1c-982e-c3957daf0aca', '6ab28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 45.2, '2020-11-17 07:00:26'),
('1c52d12d-91e4-4c1c-982e-c3957daf0aca', '9cb28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 50, '2021-01-06 07:00:26'),
('2d52d12d-91e4-4c1c-982e-c3957daf0aca', '9cb28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 34, '2020-09-14 07:00:26'),
('3e52d12d-91e4-4c1c-982e-c3957daf0aca', '9cb28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 30, '2020-11-17 07:00:26'),
('4f52d12d-91e4-4c1c-982e-c3957daf0aca', '9cb28278-91e4-11e7-b93f-2c4d544f8fe0'
  , 55, '2021-01-01 07:00:26');

