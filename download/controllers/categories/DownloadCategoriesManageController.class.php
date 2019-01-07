<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 13
 * @since   	PHPBoost 4.0 - 2014 08 24
*/

class DownloadCategoriesManageController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return DownloadService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return DownloadUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return DownloadUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return DownloadUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return DownloadUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module_title', 'common', 'download');
	}

	protected function check_authorizations()
	{
		if (!DownloadAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
