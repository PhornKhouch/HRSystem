CREATE TABLE IF NOT EXISTS `prtaxrate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `AmountFrom` decimal(10,2) NOT NULL,
  `AmountTo` decimal(10,2) NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_amount_range` (`AmountFrom`, `AmountTo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
