ALTER TABLE `0_users` ADD `show_hints` TINYINT(1) DEFAULT '0' NOT NULL AFTER `show_codes` ;



-- 
-- ALTER TABLE for ccd
-- 

ALTER TABLE `0_salesman`  ADD `loc_code` VARCHAR(5) NOT NULL DEFAULT 'DEF';

