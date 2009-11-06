<?php
/*##################################################
 *                              rss.class.php
 *                            -------------------
 *   begin                : March 10, 2005
 *   copyright            : (C) 2005 R�gis Viarre, Lo�c Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

/**
 * @author Lo�c Rouchon <loic.rouchon@phpboost.com>
 * @desc This class could load a feed by its url or by a FeedData element and
 * export it to the RSS format
 * @package content
 * @subpackage syndication
 */
class RSS extends Feed
{
	private static $default_rss_template = 'framework/content/syndication/rss.tpl';
	
    ## Public Methods ##
    /**
     * @desc Builds a new RSS object
     * @param string $module_id its module_id
     * @param string $feed_name the feeds name / type. default is Feed::DEFAULT_FEED_NAME
     * @param int $id_cat the feed category id
     */
    function RSS($module_id, $feed_name = Feed::DEFAULT_FEED_NAME, $id_cat = 0)
    {
        parent::Feed($module_id, $feed_name, $id_cat);
        $this->tpl = new Template(self::$default_rss_template);
    }

    /**
     * @desc Loads a feed by its url
     * @param string $url the feed url
     */
    function load_file($url)
    {
        if (($file = @file_get_contents_emulate($url)) !== false)
        {
            $this->data = new FeedData();
            if (preg_match('`<item>(.*)</item>`is', $file))
            {
                $expParsed = explode('<item>', $file);
                $nbItems = (count($expParsed) - 1) > $nbItems ? $nbItems : count($expParsed) - 1;
                
                $this->data->set_date(preg_match('`<!-- RSS generated by PHPBoost on (.*) -->`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_title(preg_match('`<title>(.*)</title>`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_link(preg_match('`<atom:link href="(.*)" rel="self" type="application/rss+xml" />`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_host(preg_match('`<link>(.*)</link>`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_desc(preg_match('`<description>(.*)</description>`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_lang(preg_match('`<language>(.*)</language>`is', $expParsed[0], $var) ? $var[1] : '');
                
                for ($i = 1; $i <= $nbItems; $i++)
                {
                    $item = new FeedItem();
                    
                    $item->set_title(preg_match('`<title>(.*)</title>`is', $expParsed[$i], $title) ? $title[1] : '');
                    $item->set_link(preg_match('`<link>(.*)</link>`is', $expParsed[$i], $url) ? $url[1] : '');
                    $item->set_guid(preg_match('`<guid>(.*)</guid>`is', $expParsed[$i], $guid) ? $guid[1] : '');
                    $item->set_desc(preg_match('`<desc>(.*)</desc>`is', $expParsed[$i], $desc) ? $desc[1] : '');
                    $item->set_date_rfc822(preg_match('`<pubDate>(.*)</pubDate>`is', $expParsed[$i], $date) ? gmdate_format('date_format_tiny', strtotime($date[1])) : '');
                    
                    $this->data->add_item($item);
                }
                return true;
            }
            return false;
        }
        return false;
    }
    
    ## Private Methods ##
    ## Private attributes ##
}

?>