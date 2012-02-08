<?php
/*##################################################
 *                        AdminSearchController.class.php
 *                            -------------------
 *   begin                : April 10, 2010
 *   copyright            : (C) 2010 Rouchon Loic
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

abstract class AdminSearchController extends AdminController
{
	protected function prepare_to_send(Template $view)
	{
		$lang = LangLoader::get('admin', 'search');
		$response = new AdminSearchDisplayResponse($view, $lang);
		$environment = $response->get_graphical_environment();
		$environment->set_page_title($lang['search_management']);
		return $response;
	}
}

?>