<?php

/*##################################################
 *                              PagesSearchable.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class PagesSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	/**
	 *  Renvoie la requ�te de recherche
	 */
	{
		$search = $args['search'];
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		global $_PAGES_CATS,  $Cache;
		require_once(PATH_TO_ROOT . '/pages/pages_defines.php');
		$Cache->load('pages');
		
		$auth_cats = '';
		if (is_array($_PAGES_CATS))
		{
			if (isset($_PAGES_CATS['auth']) && !AppContext::get_current_user()->check_auth($_PAGES_CATS['auth'], READ_PAGE))
				$auth_cats .= '0,';
			foreach ($_PAGES_CATS as $id => $key)
			{
				if (!AppContext::get_current_user()->check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE))
					$auth_cats .= $id.',';
			}
		}
		$auth_cats = !empty($auth_cats) ? " AND p.id_cat NOT IN (" . trim($auth_cats, ',') . ")" : '';
		
		$results = array();
		$result = PersistenceContext::get_querier()->select("SELECT ".
		$args['id_search']." AS `id_search`,
		p.id AS `id_content`,
		p.title AS `title`,
		( 2 * FT_SEARCH_RELEVANCE(p.title, '".$args['search']."') + FT_SEARCH_RELEVANCE(p.contents, '".$args['search']."') ) / 3 * " . $weight . " AS `relevance`,
		CONCAT('" . PATH_TO_ROOT . "/pages/pages.php?title=',p.encoded_title) AS `link`,
		p.auth AS `auth`
		FROM " . PREFIX . "pages p
		WHERE ( FT_SEARCH(title, '".$args['search']."') OR FT_SEARCH(contents, '".$args['search']."') )".$auth_cats . "
		LIMIT :max_search_results OFFSET 0", array(
			'max_search_result' => PAGES_MAX_SEARCH_RESULTS
		));

		while ($row = $result->fetch())
		{
			if ( !empty($row['auth']) )
			{
				$auth = unserialize($row['auth']);
				if ( !AppContext::get_current_user()->check_auth($auth, READ_PAGE) )
				{
					unset($row['auth']);
					array_push($results, $row);
				}
			}
			else
			{
				unset($row['auth']);
				array_push($results, $row);
			}
		}
		$result->dispose();
		
		return $results;
	}
}
?>