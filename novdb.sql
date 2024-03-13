ALTER TABLE `college_representatives` ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `colleges` ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `users` ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `agents` ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;

ALTER TABLE `loan_contracts` ADD `current_past_due_days` INT NOT NULL DEFAULT '0' AFTER `penalt_amount_paid`;

