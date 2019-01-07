<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 09 20
 * @since   	PHPBoost 1.2 - 2005 10 25
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Config
	new UrlControllerMapper('AdminForumConfigController', '`^/admin(?:/config)?/?$`'),

	//Categories
	new UrlControllerMapper('ForumCategoriesManageController', '`^/categories/?$`'),
	new UrlControllerMapper('ForumCategoriesFormController', '`^/categories/add/?$`'),
	new UrlControllerMapper('ForumCategoriesFormController', '`^/categories/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('ForumDeleteCategoryController', '`^/categories/([0-9]+)/delete/?$`', array('id')),

	new UrlControllerMapper('ForumHomeController', '`^/?$`'),
);
DispatchManager::dispatch($url_controller_mappers);
?>
