
DROP TABLE IF EXISTS `material`;
CREATE TABLE IF NOT EXISTS `material` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `material_code` text DEFAULT NULL,
  `material_unit_id` int(6) UNSIGNED DEFAULT NULL,
  `material_group_id` int(6) UNSIGNED DEFAULT NULL,
  `material_name` text DEFAULT NULL,
  `material_detail` text DEFAULT NULL,
  `picture`  text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `material_gen_qr`;
CREATE TABLE IF NOT EXISTS `material_gen_qr` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `material_code` int(6) UNSIGNED DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `material_unit`;
CREATE TABLE IF NOT EXISTS `material_unit` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `material_group`;
CREATE TABLE IF NOT EXISTS `material_group` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quotation_order_item`;
CREATE TABLE IF NOT EXISTS `quotation_order_item` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_quotation` text DEFAULT NULL,
  `material_code` text DEFAULT NULL,
  `material_name` text DEFAULT NULL,
  `material_detail` text DEFAULT NULL,
  `or_number` float DEFAULT 0,
  `or_unit_price` float DEFAULT 0,
  `or_total_price` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quotation_order`;
CREATE TABLE IF NOT EXISTS `quotation_order` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_qoutation` text DEFAULT NULL,
  `doc_recive` text DEFAULT NULL,
  `doc_pay` text DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `recive_date` date DEFAULT NULL,
  `vat` int(6) UNSIGNED DEFAULT NULL,
  `order_employee_id` int(6) UNSIGNED DEFAULT NULL,
  `order_employee` text DEFAULT NULL,
  `order_tel` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `rec_shop_id` int(6) UNSIGNED DEFAULT NULL,
  `rec_shop` text DEFAULT NULL,
  `rec_remark` text DEFAULT NULL,
  `supplier_id` int(6) UNSIGNED DEFAULT NULL,
  `supplier` text DEFAULT NULL,
  `supplier_detial` text DEFAULT NULL,
  `supplier_contact_id` int(6) UNSIGNED DEFAULT NULL,
  `supplier_contact` text DEFAULT NULL,
  `supplier_contact_detial` text DEFAULT NULL,
  `sum_sub_total` float DEFAULT 0,
  `sum_vat` float DEFAULT 0,
  `sum_grand_total` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `member_employee`;
CREATE TABLE IF NOT EXISTS `member_employee` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_quotation` text DEFAULT NULL,
  `material_code` text DEFAULT NULL,
  `material_name` text DEFAULT NULL,
  `material_detail` text DEFAULT NULL,
  `or_number` float DEFAULT 0,
  `or_unit_price` float DEFAULT 0,
  `or_total_price` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quotation_recive_item`;
CREATE TABLE IF NOT EXISTS `quotation_recive_item` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_item_id` int(6) UNSIGNED DEFAULT NULL,
  `doc_recive` text DEFAULT NULL,
  `rec_number_recived` float DEFAULT 0,
  `rec_number_wait` float DEFAULT 0,
  `rec_total_price` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quotation_recive`;
CREATE TABLE IF NOT EXISTS `quotation_recive` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_qoutation` text DEFAULT NULL,
  `doc_recive` text DEFAULT NULL,
  `doc_pay` text DEFAULT NULL,
  `revice_date` date DEFAULT NULL,
  `sum_subtotal` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `quotation_pay`;
CREATE TABLE IF NOT EXISTS `quotation_pay` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `doc_qoutation` text DEFAULT NULL,
  `doc_recive` text DEFAULT NULL,
  `doc_pay` text DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `sum_subtotal` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sup_group_id` int(6) UNSIGNED DEFAULT NULL,
  `name` text DEFAULT NULL,
  `tax` text DEFAULT NULL,
  `branch` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `supplier_group`;
CREATE TABLE IF NOT EXISTS `supplier_group` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` int(6) UNSIGNED DEFAULT NULL,
  `title_name`  int(6) UNSIGNED DEFAULT NULL,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `tel` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supplier_contact_group`;
CREATE TABLE IF NOT EXISTS `supplier_contact_group` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `material_id` int(6) UNSIGNED DEFAULT NULL,
  `stock_number`  float DEFAULT 0,
  `stcok_unit_price` float DEFAULT 0,
  `stock_updadte_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stock_update_status` text DEFAULT NULL,
  `stock_update_by` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_code` text DEFAULT NULL,
  `product_name` text DEFAULT NULL,
  `product_detail` text DEFAULT NULL,
  `product_price` float DEFAULT 0,
  `product_picture` text DEFAULT NULL,
  `product_remark` text DEFAULT NULL,
  `product_group_id` int(6) UNSIGNED DEFAULT NULL,
  `product_unit_id` int(6) UNSIGNED DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `product_item`;
CREATE TABLE IF NOT EXISTS `product_item` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(6) UNSIGNED DEFAULT NULL,
  `material_code` text DEFAULT NULL,
  `product_item_type_id` int(6) UNSIGNED DEFAULT NULL,
  `product_item_number` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_ingredient`;
CREATE TABLE IF NOT EXISTS `product_ingredient` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(6) UNSIGNED DEFAULT NULL,
  `ingredient_list_id` int(6) UNSIGNED DEFAULT NULL,
  `product_ingredient_min` float DEFAULT 0,
  `product_ingredient_middle` float DEFAULT 0,
  `product_ingredient_max` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_unit`;
CREATE TABLE IF NOT EXISTS `product_unit` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_unit_name` text DEFAULT NULL,
  `product_unit_detail` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_group`;
CREATE TABLE IF NOT EXISTS `product_group` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_group_name` text DEFAULT NULL,
  `product_group_detail` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_item_type`;
CREATE TABLE IF NOT EXISTS `product_item_type` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(6) UNSIGNED DEFAULT NULL,
  `product_item_type_name` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ingredient_list`;
CREATE TABLE IF NOT EXISTS `ingredient_list` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ingredient_list_name` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_sales`;
CREATE TABLE IF NOT EXISTS `product_sales` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_group_id` int(6) UNSIGNED DEFAULT NULL,
  `product_id` int(6) UNSIGNED DEFAULT NULL,
  `employee_id` int(6) UNSIGNED DEFAULT NULL,
  `employee_name` text DEFAULT NULL,
  `product_item_type_id` int(6) UNSIGNED DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `sales_sum_subtotal` float DEFAULT 0,
  `sales_discount` float DEFAULT 0,
  `sales_special_discount` float DEFAULT 0,
  `sales_sum_grandtotal` float DEFAULT 0,
  `sales_queue` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_sales_item`;
CREATE TABLE IF NOT EXISTS `product_sales_item` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_sales_id` int(6) UNSIGNED DEFAULT NULL,
  `product_id` int(6) UNSIGNED DEFAULT NULL,
  `product_code` text DEFAULT NULL,
  `product_name` text DEFAULT NULL,
  `product_detail` text DEFAULT NULL,
  `product_price` float DEFAULT 0,
  `product_unit` text DEFAULT NULL,
  `item_sum_total` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_sales_ingredient`;
CREATE TABLE IF NOT EXISTS `product_sales_ingredient` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_sales_item_id` int(6) UNSIGNED DEFAULT NULL,
  `product_id` int(6) UNSIGNED DEFAULT NULL,
  `product_ingredient_id` int(6) UNSIGNED DEFAULT NULL,
  `product_ingredient_status` int(6) UNSIGNED DEFAULT 1,
  `product_ingredien_val` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stock_inventory`;
CREATE TABLE IF NOT EXISTS `stock_inventory` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inv_doc` text DEFAULT NULL,
  `inv_date` date DEFAULT NULL,
  `employee_id` int(6) UNSIGNED DEFAULT NULL,
  `employee_name` text DEFAULT NULL,
  `inv_time_start` time DEFAULT NULL,
  `inv_time_end` time DEFAULT NULL,
  `inv_remark` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stock_inventory_item`;
CREATE TABLE IF NOT EXISTS `stock_inventory_item` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inv_id` int(6) UNSIGNED DEFAULT 1,
  `mat_get_qr_id` int(6) UNSIGNED DEFAULT NULL,
  `material_code` int(6) UNSIGNED DEFAULT NULL,
  `material_name` text DEFAULT NULL,
  `counted` float DEFAULT 0,
  `notcount` float DEFAULT 0,
  `total` float DEFAULT 0,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stock_inventory_result`;
CREATE TABLE IF NOT EXISTS `stock_inventory_result` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inv_id` int(6) UNSIGNED DEFAULT NULL,
  `material_code` int(6) UNSIGNED DEFAULT NULL,
  `material_name` text DEFAULT NULL,
  `inv_result_number` float DEFAULT 0,
  `inv_resual_status` text DEFAULT NULL,
  `active` BOOLEAN NOT NULL DEFAULT 1,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `service_renew`;
CREATE TABLE service_renew
(
  id             INT       NOT NULL AUTO_INCREMENT,
  tb_pay_mode_id INT       NOT NULL,
  renew_shop     TEXT      NULL    ,
  renew_tex      TEXT      NULL    ,
  renew_addr     TEXT      NULL    ,
  active         BOOLEAN   NOT NULL DEFAULT 1,
  reg_date       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `service_renew_item`;
CREATE TABLE service_renew_item
(
  id               INT       NOT NULL AUTO_INCREMENT,
  service_renew_id INT       NOT NULL,
  pakage_id        INT       NOT NULL,
  payin_doc        TEXT      NULL    ,
  pakage_name      TEXT      NULL    ,
  date_start       DATE      NULL    ,
  date_end         DATE      NULL    ,
  price            FLOAT     NULL    ,
  active           BOOLEAN   NOT NULL DEFAULT 1,
  reg_date         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `service_report`;
CREATE TABLE service_report
(
  id                 INT       NOT NULL AUTO_INCREMENT,
  member_shop_id     INT       NOT NULL,
  member_employee_id INT       NULL    ,
  subject            TEXT      NULL    ,
  detail             TEXT      NULL    ,
  date               DATE      NULL    ,
  active             BOOLEAN   NOT NULL DEFAULT 1,
  reg_date           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


