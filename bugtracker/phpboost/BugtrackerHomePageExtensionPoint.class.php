<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 3.0 - 2012 04 16
*/

class BugtrackerHomePageExtensionPoint implements HomePageExtensionPoint
{
	 /**
	 * @method Get the module home page
	 */
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), BugtrackerUnsolvedListController::get_view());
	}

	 /**
	 * @method Get the module title
	 */
	private function get_title()
	{
		return LangLoader::get_message('module_title', 'common', 'bugtracker');
	}
}
?>