<?php
/*##################################################
 *                       DefaultTemplateData.class.php
 *                            -------------------
 *   begin                : February 19, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class DefaultTemplateData implements TemplateData
{
	protected $langs = array();
	protected $vars = array();
	protected $blocks = array();
	protected $subtemplates = array();

	/**
	 * @desc Assigns some simple template vars.  Those variables will be accessed in your template with the {var_name} syntax.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	public function assign_vars(array $array_vars)
	{
		foreach ($array_vars as $key => $val)
		{
			$this->vars[$key] = $val;
		}
	}

	/**
	 * @desc Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	function assign_block_vars($block_name, array $array_vars, array $subtemplates = array())
	{
		if (strpos($block_name, '.') !== false) //Bloc imbriqu�.
		{
			$blocks = explode('.', $block_name);
			$blockcount = count($blocks) - 1;

			$str = &$this->blocks;
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[count($str) - 1];
			}
			$str[$blocks[$blockcount]][] = array(
				'vars' => $array_vars,
				'subtemplates' => $subtemplates
			);
		}
		else
		{
			$this->blocks[$block_name][] = array(
				'vars' => $array_vars,
				'subtemplates' => $subtemplates
			);
		}
	}

	/**
	 * @desc add a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
	 * @param string[string] $lang the language map
	 */
	function add_lang(array $lang)
	{
		$this->langs[] = $lang;
	}

	/**
	 * @desc add a subtemplate that could be used using the following template code <code># include identifier #</code>
	 * @param string $identifier the identifier
	 * @param Template $template the template
	 */
	function add_subtemplate($identifier, Template $template)
	{
		$this->subtemplates[$identifier] =& $template;
	}

	/**
	 * @desc returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	function get_subtemplate($identifier)
	{
		if (isset($this->subtemplates[$identifier]))
		{
			return $this->subtemplates[$identifier];
		}
		return null;
	}

	/**
	 * @desc returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @param Template[string] $list the template list in which we will the search for the
	 * template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	function get_subtemplate_from_list($identifier, $list)
	{
		if (isset($list[$identifier]))
		{
			return $list[$identifier];
		}
	}


	/**
	 * @desc returns the block "blockname" in the template block list
	 * @param string $blockname the blockname of the block to retrieve
	 * @return mixed[] the requested block
	 */
	function get_block($blockname)
	{
		return $this->get_block_from_list($blockname, $this->blocks);
	}

	/**
	 * @desc returns the block "blockname" in the parent_block
	 * @param string $blockname the blockname of the block to retrieve
	 * @param mixed[] $parent_block the parent block in which $blockname will be searched for
	 * @return mixed[] the requested block
	 */
	function get_block_from_list($blockname, $parent_block)
	{
		if (isset($parent_block[$blockname]) && is_array($parent_block[$blockname]))
		{
			return $parent_block[$blockname];
		}
		return array();
	}

	/**
	 * @desc Returns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	function is_true($varname)
	{
		return $this->is_true_from_list($varname, $this->vars);
	}

	/**
	 * @desc rReturns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @param mixed[] $list the array in which we varname will be searched for
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	function is_true_from_list($varname, $list)
	{
		return isset($list[$varname]) && $list[$varname];
	}

	/**
	 * @desc Returns the $varname variable content
	 * @param string $varname the name of the variable to retrieve
	 * @return string the $varname variable content
	 * @see get_var_from_list($varname, $list)
	 */
	/**
	 * @desc Returns the $varname variable content searched in from the $list
	 * Special operations will be done if the variable is not registered in $list. If $varname begins with
	 * <ul>
	 *	<li><E_: the variable will be search without its prefix and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><J_: the variable will be search without its prefix and will be escaped using <code>TextHelper::to_js_string()</code></li>
	 *	<li><L_: the variable will be search without its prefix in every languages maps registered using <code>Template->add_lang($language)</code></li>
	 *	<li><EL_: the variable will be search without its prefix like languages variables and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><JL_: the variable will be search without its prefix like languages variables and will be escaped using <code>TextHelper::to_js_string()</code></li>
	 * </ul>
	 * Each time one of these operation is requested, the variable is registered in order to speed up next calls. If nothing is found, then an empty string is returned
	 * @param string $varname the name of the variable to retrieve
	 * @param mixed[] $list the list in which the variable will be searched for
	 * @return string the $varname variable content
	 */

	function get_var($varname)
	{
		return $this->get_var_from_list($varname, $this->vars);
	}

	function get_var_from_list($varname, &$list)
	{
		if (isset($list[$varname]))
		{
			return $list[$varname];
		}
		$empty_value = '';
		return $this->register_var($varname, $empty_value, $list);
	}

	function get_js_var($varname)
	{
		return $this->get_js_var_from_list($varname, $this->vars);
	}

	function get_js_var_from_list($varname, &$list)
	{
		$full_varname = 'J_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		if (!isset($list[$varname]))
		{
			$list[$varname] = '';
		}
		return $this->register_var($full_varname, TextHelper::to_js_string($list[$varname]), $list);
	}

	function get_js_lang_var($varname)
	{
		return $this->get_js_lang_var_from_list($varname, $this->vars);
	}

	function get_js_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'JL_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		$lang_var = $this->get_lang_var_from_list($varname, $list);
		return $this->register_var($full_varname, TextHelper::to_js_string($lang_var), $list);
	}


	function get_htmlescaped_lang_var($varname)
	{
		return $this->get_htmlescaped_lang_var_from_list($varname, $this->vars);
	}

	function get_htmlescaped_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'EL_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		$lang_var = $this->get_lang_var_from_list($varname, $list);
		return $this->register_var($full_varname, htmlspecialchars($lang_var), $list);
	}

	function get_htmlescaped_var($varname)
	{
		return $this->get_htmlescaped_var_from_list($varname, $this->vars);
	}

	function get_htmlescaped_var_from_list($varname, &$list)
	{
		$full_varname = 'E_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		if (!isset($list[$varname]))
		{
			$list[$varname] = '';
		}

		$value = htmlspecialchars($list[$varname]);
		return $this->register_var($full_varname, $value, $list);
	}

	function get_lang_var($varname)
	{
		return $this->get_lang_var_from_list($varname, $this->vars);
	}

	function get_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'L_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}
		$varname= strtolower($varname);
		foreach ($this->langs as $lang)
		{
			if (isset($lang[$varname]))
			{
				return $this->register_var($full_varname, $lang[$varname], $list);
			}
		}
		$empty_string = '';
		return $this->register_var($full_varname, $empty_string, $list);
	}

	public function auto_load_frequent_vars()
	{
		global $User, $Session;
		$member_connected = $User->check_level(MEMBER_LEVEL);
		$this->assign_vars(array(
			'SID' => SID,
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'C_USER_CONNECTED' => $member_connected,
			'C_USER_NOTCONNECTED' => !$member_connected,
			'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
			'PHP_PATH_TO_ROOT' => PATH_TO_ROOT,
			'TOKEN' => !empty($Session) ? $Session->get_token() : ''
			));
	}

	private function find_lang_var($varname)
	{
		foreach ($this->langs as $lang)
		{
			if (isset($lang[$varname]))
			{
				return $lang[$varname];
			}
		}
		return '';
	}

	private function register_var($name, $value, &$list)
	{
		$list[$name] = $value;
		return $value;
	}
	
	public function bind_vars(TemplateData $data)
	{
		$data->vars =& $this->vars;
		$data->blocks =& $this->blocks;
		$data->langs =& $this->langs;
		$data->subtemplates =& $this->subtemplates;
	}
}
?>