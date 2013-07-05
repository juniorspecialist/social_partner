-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 05 2013 г., 13:05
-- Версия сервера: 5.5.31
-- Версия PHP: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `secret`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_billings`
--

CREATE TABLE IF NOT EXISTS `tbl_billings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) NOT NULL COMMENT 'партнер',
  `amount` decimal(10,2) NOT NULL COMMENT 'сумма пополнения',
  `create_at` int(11) NOT NULL COMMENT 'дата выставленного счёта на пополнение',
  `status` tinyint(1) NOT NULL COMMENT 'статус(1-выставлен,2- оплачен)',
  `type_money_system` tinyint(2) NOT NULL COMMENT 'тип денежной системы, через которую происходит пополнение баланса',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='выставленные счета на пополнение баланса в системе' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_buying_partnership_set`
--

CREATE TABLE IF NOT EXISTS `tbl_buying_partnership_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` int(11) unsigned NOT NULL COMMENT 'для кого покупается партнёрский комплект',
  `create_at` int(11) unsigned NOT NULL,
  `partnership_set_id` smallint(2) unsigned NOT NULL,
  `type_buying` tinyint(4) unsigned NOT NULL COMMENT 'тип покупаемого комплекта(1-именной,0-не именной)',
  `who_buys` int(11) unsigned NOT NULL COMMENT 'кто покупает партнёрский комплект',
  PRIMARY KEY (`id`),
  KEY `partner_index` (`partner_id`),
  KEY `FK_tbl_buying_partnership_set_tbl_partnership_set` (`partnership_set_id`),
  KEY `FK_tbl_buying_partnership_set_tbl_partner_2` (`who_buys`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='приобретения партнёрских комплектов' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_cashout`
--

CREATE TABLE IF NOT EXISTS `tbl_cashout` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_cash` tinyint(3) unsigned NOT NULL COMMENT 'способ выплаты',
  `create_at` int(10) unsigned NOT NULL,
  `sum_cash` decimal(10,2) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `partner_id` int(11) unsigned NOT NULL COMMENT 'партнёр отправивший заявку на вывод',
  `desc` text NOT NULL COMMENT 'реквизиты выводы средств к заявке',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_cashout_tbl_partner` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Заявки на вывод средств из системы' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_config`
--

CREATE TABLE IF NOT EXISTS `tbl_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `param` varchar(128) NOT NULL,
  `value` text NOT NULL,
  `default` text NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `param` (`param`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_finance_partnership`
--

CREATE TABLE IF NOT EXISTS `tbl_finance_partnership` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `destination_account` int(10) unsigned NOT NULL COMMENT 'Получатель бонуса(номер счета текущего пользователя)',
  `point` decimal(10,2) unsigned NOT NULL COMMENT 'Размер бонуса, баллы',
  `sender_account` int(10) unsigned NOT NULL COMMENT 'Отправитель бонуса(номер счета)',
  `has_partners` int(10) unsigned NOT NULL COMMENT 'Кол-во пользователей в статусе Партнер у Вас на момент совершения транзакции',
  `has_personal_partners` int(10) unsigned NOT NULL COMMENT 'Кол-во личных Партнерских комплектов у Вас на момент совершения транзакции',
  `active_points` int(10) unsigned NOT NULL COMMENT 'Кол-во баллов активности у Вас на момент совершения транзакции',
  `partner_level` smallint(5) unsigned NOT NULL COMMENT 'Ваш уровень в Партнерской программе на момент совершения транзакции',
  `active_points_sender` int(10) unsigned NOT NULL COMMENT 'Кол-во баллов активности у отправителя на момент совершения транзакции',
  `partner_level_sender` smallint(5) unsigned NOT NULL COMMENT 'Уровень отправителя в Партнерской Программе на момент совершения транзакции',
  `level_cooperator` int(10) unsigned NOT NULL COMMENT 'Уровень сотрудника как Вашего реферала',
  `create_at` int(10) unsigned NOT NULL COMMENT 'Дата и время совершения транзакции',
  `bonus_from_level1` smallint(5) unsigned NOT NULL COMMENT 'Ваш бонус с 1 уровня рефералов на момент совершения транзакции, %',
  `bonus_from_other_levels` smallint(5) unsigned NOT NULL COMMENT 'Ваш бонус с 2-10 уровней на момент совершения транзакции, %',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_finance_partnership_tbl_partner` (`destination_account`),
  KEY `FK_tbl_finance_partnership_tbl_partner_2` (`sender_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Доходы по партнёрской программе' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_history_account`
--

CREATE TABLE IF NOT EXISTS `tbl_history_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_operation` tinyint(1) unsigned NOT NULL COMMENT 'Тип операции(приход,расход)',
  `partner_id` int(10) unsigned NOT NULL COMMENT 'Получатель бонуса(партнёр)',
  `bonuse` decimal(10,2) unsigned NOT NULL COMMENT 'Размер бонуса, баллы',
  `destination` tinyint(3) unsigned NOT NULL COMMENT 'Назначение(1-перевод,2-доход,3-покупка в каталоге)',
  `create_at` int(10) unsigned NOT NULL,
  `bonus_sender` int(11) unsigned NOT NULL COMMENT 'Отправитель бонуса(ID партнёра)',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_history_account_tbl_partner` (`partner_id`),
  KEY `FK_tbl_history_account_tbl_partner_2` (`bonus_sender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='История платежей' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_invitations`
--

CREATE TABLE IF NOT EXISTS `tbl_invitations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `create_at` int(11) NOT NULL,
  `invitations_text` varchar(256) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `partner_id` int(11) unsigned NOT NULL,
  `phone` varchar(20) NOT NULL COMMENT 'номер телефону, кому отправляем приглашение',
  `service_id` varchar(32) NOT NULL COMMENT 'ID смс, которую мы отправили, на стороне смс-сервиса',
  PRIMARY KEY (`id`),
  KEY `FK_tbl_invitations_tbl_partner` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='приглашения отправленные юзерами, через смс' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_partner`
--

CREATE TABLE IF NOT EXISTS `tbl_partner` (
  `id` int(11) unsigned NOT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `partner_soc_id` int(11) unsigned NOT NULL COMMENT 'ID юзера из соц. движка',
  `fio` varchar(255) NOT NULL COMMENT 'ФИО партнёра',
  `phone` varchar(20) NOT NULL COMMENT 'номер мобильного',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT 'статус партнёра(1-участник, 2 - партнёр)',
  `partners_level_1` int(11) unsigned NOT NULL COMMENT 'кол-во партнёров на уровне 1',
  `partner_level` tinyint(3) unsigned NOT NULL COMMENT 'уровень партнёра(0-нет уровня,1-серебряный,2-золотой,3-платиновый,4-бриллиантовый)',
  `active_points` int(10) unsigned NOT NULL COMMENT 'кол-во баллов активности',
  `bonus_from_other_levels` tinyint(3) unsigned NOT NULL COMMENT 'процент с покупок партнёрских комплектов по нижестоящим уровням',
  `email` varchar(255) NOT NULL,
  `balance` decimal(10,2) NOT NULL COMMENT 'баланс юзера(голоса)',
  `password` varchar(50) NOT NULL,
  `role` tinyint(1) NOT NULL COMMENT 'роль пользователя(0-юзер,1-админ)',
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='партнёрская иерархия(дерево партнеров)';

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_partnership_set`
--

CREATE TABLE IF NOT EXISTS `tbl_partnership_set` (
  `id` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `img` varchar(255) NOT NULL COMMENT 'путь к фотке товара',
  `cost_price` int(11) NOT NULL COMMENT 'себестоимость',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='партнёрские комплекты' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_pm`
--

CREATE TABLE IF NOT EXISTS `tbl_pm` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT 'текст сообщения',
  `partner_sender` int(10) unsigned NOT NULL COMMENT 'партнёр отправитель',
  `partner_destination` int(10) unsigned NOT NULL COMMENT 'партнёр получатель',
  `status` tinyint(3) unsigned NOT NULL COMMENT 'статус(0-не прочитанно,1-прочитанно)',
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_pm_tbl_partner` (`partner_sender`),
  KEY `FK_tbl_pm_tbl_partner_2` (`partner_destination`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='личные сообщения по партнёрам' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_setting`
--

CREATE TABLE IF NOT EXISTS `tbl_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='настройки' AUTO_INCREMENT=1 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `tbl_buying_partnership_set`
--
ALTER TABLE `tbl_buying_partnership_set`
  ADD CONSTRAINT `FK_tbl_buying_partnership_set_tbl_partner` FOREIGN KEY (`partner_id`) REFERENCES `tbl_partner` (`id`),
  ADD CONSTRAINT `FK_tbl_buying_partnership_set_tbl_partnership_set` FOREIGN KEY (`partnership_set_id`) REFERENCES `tbl_partnership_set` (`id`),
  ADD CONSTRAINT `FK_tbl_buying_partnership_set_tbl_partner_2` FOREIGN KEY (`who_buys`) REFERENCES `tbl_partner` (`id`);

--
-- Ограничения внешнего ключа таблицы `tbl_cashout`
--
ALTER TABLE `tbl_cashout`
  ADD CONSTRAINT `FK_tbl_cashout_tbl_partner` FOREIGN KEY (`partner_id`) REFERENCES `tbl_partner` (`id`);

--
-- Ограничения внешнего ключа таблицы `tbl_finance_partnership`
--
ALTER TABLE `tbl_finance_partnership`
  ADD CONSTRAINT `FK_tbl_finance_partnership_tbl_partner` FOREIGN KEY (`destination_account`) REFERENCES `tbl_partner` (`id`),
  ADD CONSTRAINT `FK_tbl_finance_partnership_tbl_partner_2` FOREIGN KEY (`sender_account`) REFERENCES `tbl_partner` (`id`);

--
-- Ограничения внешнего ключа таблицы `tbl_history_account`
--
ALTER TABLE `tbl_history_account`
  ADD CONSTRAINT `FK_tbl_history_account_tbl_partner` FOREIGN KEY (`partner_id`) REFERENCES `tbl_partner` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tbl_history_account_tbl_partner_2` FOREIGN KEY (`bonus_sender`) REFERENCES `tbl_partner` (`id`);

--
-- Ограничения внешнего ключа таблицы `tbl_invitations`
--
ALTER TABLE `tbl_invitations`
  ADD CONSTRAINT `FK_tbl_invitations_tbl_partner` FOREIGN KEY (`partner_id`) REFERENCES `tbl_partner` (`id`);

--
-- Ограничения внешнего ключа таблицы `tbl_pm`
--
ALTER TABLE `tbl_pm`
  ADD CONSTRAINT `FK_tbl_pm_tbl_partner` FOREIGN KEY (`partner_sender`) REFERENCES `tbl_partner` (`id`),
  ADD CONSTRAINT `FK_tbl_pm_tbl_partner_2` FOREIGN KEY (`partner_destination`) REFERENCES `tbl_partner` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
