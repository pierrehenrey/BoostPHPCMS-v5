<?php
/*##################################################
 *                          NewsletterModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class NewsletterModuleMiniMenu extends ModuleMiniMenu
{    
	public function get_default_block()
	{
		return self::BLOCK_POSITION__TOP_FOOTER;
	}
	
	public function admin_display()
	{
		return '';
	}

	public function display($tpl = false)
	{
		 if (NewsletterAuthorizationsService::check_authorizations()->subscribe())
		{
			$tpl = new FileTemplate('newsletter/newsletter_mini.tpl');
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$lang = LangLoader::get('common', 'newsletter');
			$tpl->add_lang($lang);
			
			$tpl->put('USER_MAIL', AppContext::get_current_user()->get_email());
			
			return $tpl->render();
		}
		return '';
	}
}
?>