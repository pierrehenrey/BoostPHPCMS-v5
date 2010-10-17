<?php
/*##################################################
 *                       SearchableExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 08, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

abstract class AbstractSearchableExtensionPoint implements SearchableExtensionPoint
{
    /**
	 * {@inheritDoc}
     */
    public function has_search_options()
    {
    	return false;
    }

    /**
	 * {@inheritDoc}
     */
    public function build_search_form(array $values)
    {
    	throw UnsupportedOperationException();
    }

    /**
	 * {@inheritDoc}
     */
    public function build_output_as_list()
    {
    	return true;
    }

    /**
	 * {@inheritDoc}
     */
    public function format_element()
    {
		$tpl = new FileTemplate('framework/content/search/SearchGenericResult.tpl');
		$tpl->put();
		return $tpl;
    }
}
?>