<?php
/*##################################################
 *                           AdminErrorsDisplayResponse.class.php
 *                            -------------------
 *   begin                : December 13 2009
 *   copyright            : (C) 2009 Lo�c Rouchon
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 */
class AdminErrorsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
        parent::__construct($view);
        $lang = LangLoader::get('admin-errors-Common');
        $view->add_lang($lang);

        $img = '/templates/' . get_utheme() . '/images/admin/errors.png';
        $this->set_title($lang['logged_errors']);
        $this->add_link($lang['logged_errors'], AdminErrorsUrlBuilder::logged_errors()->absolute(), $img);
        $this->add_link($lang['404_errors'], AdminErrorsUrlBuilder::list_404_errors()->absolute(), $img);
	}
}
?>