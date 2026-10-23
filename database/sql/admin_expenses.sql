CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'other',
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'BDT',
  `billing_cycle` varchar(30) NOT NULL DEFAULT 'one_time',
  `expense_date` date NOT NULL,
  `renewal_date` date DEFAULT NULL,
  `vendor` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_type_expense_date_index` (`type`, `expense_date`),
  KEY `expenses_renewal_date_index` (`renewal_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
