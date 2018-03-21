-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.17 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping data for table spike2care.admin: ~3 rows (approximately)
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` (`id`, `role_id`, `user_name`, `password`, `email`, `is_active`, `updated_at`) VALUES
	(1, 1, 'Tyler Jaquish', '$2y$10$gak5fRxPStRV4hWkZnfg7.EGlf9bJ9TvMU/K3QKQmEGib.Zwr4hx2', 'tylerjaquish@gmail.com', 1, '2017-08-02 19:38:50'),
	(2, 3, 'Keva Sonderen', '$2y$10$gak5fRxPStRV4hWkZnfg7.EGlf9bJ9TvMU/K3QKQmEGib.Zwr4hx2', 'keva@s2c.org', 1, '2017-07-29 11:26:26'),
	(3, 2, 'Joel Evans', '$2y$10$gak5fRxPStRV4hWkZnfg7.EGlf9bJ9TvMU/K3QKQmEGib.Zwr4hx2', 'joel@s2c.org', 1, '2017-08-02 19:32:42');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
