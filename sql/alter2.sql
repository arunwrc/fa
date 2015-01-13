ALTER TABLE `0_users` ADD `show_hints` TINYINT(1) DEFAULT '0' NOT NULL AFTER `show_codes` ;



-- 
-- ALTER TABLE for ccd
-- 

ALTER TABLE `0_salesman`  ADD `loc_code` VARCHAR(5) NOT NULL DEFAULT 'DEF';

ALTER TABLE `0_sales_orders`  ADD `salesman_code` INT(11) NOT NULL DEFAULT '1' AFTER `from_stk_loc`;

