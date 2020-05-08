<?php
/**
 * @copyright 	&copy; 2005-2020 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2020 05 08
 * @since   	PHPBoost 5.3 - 2020 05 08
*/

class CalendarCategoriesManagementController extends DefaultCategoriesManagementController
{
	protected function get_display_category_url(Category $category)
	{
		return CalendarUrlBuilder::home();
	}
}
?>