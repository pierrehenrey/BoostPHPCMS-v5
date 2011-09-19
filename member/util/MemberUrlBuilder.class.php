<?php
/*##################################################
 *                          MemberUrlBuilder.class.php
 *                            -------------------
 *   begin                : September 19, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @desc
 */
class MemberUrlBuilder
{
    private static $dispatcher = '/member';
    

	/**
	 * @return Url
	 */
    public static function list_members($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, $param);
	}
	
	/**
	 * @return Url
	 */
    public static function groups()
	{
		//TODO 
		return DispatchManager::get_url(self::$dispatcher, '/group/');
	}
    
	/**
	 * @return Url
	 */
    public static function profile($user_id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/profile/' . $user_id);
	}
	
	/**
	 * @return Url
	 */
    public static function edit_profile()
	{
		return DispatchManager::get_url(self::$dispatcher, '/profile/edit/');
	}
	
	/**
	 * @return Url
	 */
    public static function home_profile()
	{
		return DispatchManager::get_url(self::$dispatcher, '/profile/home/');
	}
	
	/**
	 * @return Url
	 */
    public static function register()
	{
		return DispatchManager::get_url(self::$dispatcher, '/register/');
	}
	
	/**
	 * @return Url
	 */
    public static function lost_password($key = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/forget/' . $key);
	}
	
	/**
	 * @return Url
	 */
    public static function confirm_registeration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/confirm/' . $key);
	}
}
?>