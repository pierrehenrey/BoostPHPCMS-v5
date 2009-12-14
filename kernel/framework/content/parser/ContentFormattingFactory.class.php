<?php
/*##################################################
 *                         content_formatting_factory.class.php
 *                            -------------------
 *   begin                : July 3 2008
 *   copyright            : (C) 2008 Benoit Sautel
 *   email                :  ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
 * @package content
 * @subpackage parser
 * @author Beno�t Sautel <ben.popeye@phpboost.com>
 * @desc This class is approximatively a factory which provides objets capable to format some content.
 * The text formatting uses a syntax, PHPBoost supports both the BBCode syntax and a WYSIWYG tool syntax (TinyMCE).
 * You can choose the formatting type you want to deal with.
 */
class ContentFormattingFactory
{
	const BBCODE_LANGUAGE = 'bbcode';
	const TINYMCE_LANGUAGE = 'tinymce';
	const DEFAULT_LANGUAGE = 'default';

	/**
	 * @var string Language type
	 */
	private $language_type = self::DEFAULT_LANGUAGE;

	/**
	 * @desc Builds a ContentFormattingFactoryy object
	 * @param string $language_type The language in which must work the factory. One of the following elements:
	 * <ul>
	 * 	<li>BBCODE_LANGUAGE if you want to force the BBCode language</li>
	 * 	<li>TINYMCE_LANGUAGE if you want to force the TinyMCE formatting editor</li>
	 * 	<li>DEFAULT_LANGUAGE if you want to use the default language of the site. If the user changed it, it will be
	 * the language he chosed, otherwise it will be the website default language.</li>
	 * </ul>
	 */
	public function __construct($language_type = false)
	{
		if ($language_type !== false)
		{
			$this->set_language($language_type);
		}
	}

	/**
	 * @desc Changes the language of the factory
	 * @param string $language_type The language in which must work the factory. One of the following elements:
	 * <ul>
	 * 	<li>BBCODE_LANGUAGE if you want to force the BBCode language</li>
	 * 	<li>TINYMCE_LANGUAGE if you want to force the TinyMCE formatting editor</li>
	 * 	<li>DEFAULT_LANGUAGE if you want to use the default language of the site. If the user changed it, it will be
	 * the language he chosed, otherwise it will be the website default language.</li>
	 * </ul>
	 */
	public function set_language($language_type = self::DEFAULT_LANGUAGE)
	{
		//If the language type is specified and correct
		if (in_array($language_type, array(self::BBCODE_LANGUAGE, self::TINYMCE_LANGUAGE)))
		{
			$this->language_type = $language_type;
		}
		else
		{
			$this->language_type = self::DEFAULT_LANGUAGE;
		}
	}

	/**
	 * @desc Returns the language of the factory
	 * @return string Language (BBCODE_LANGUAGE, TINYMCE_LANGUAGE or DEFAULT_LANGUAGE)
	 */
	public function get_language()
	{
		return $this->language_type;
	}

	/**
	 * @desc Returns a parser which will work in the language you chose.
	 * @return ContentParser The parser to use to parse you formatting
	 */
	public function get_parser()
	{
		global $CONFIG;
		switch ($this->language_type)
		{
			case self::BBCODE_LANGUAGE:
				return new BBCodeParser();
			case self::TINYMCE_LANGUAGE:
				return new TinyMCEParser();
			default:
				if ($this->get_user_editor() == self::TINYMCE_LANGUAGE)
				{
					return new TinyMCEParser();
				}
				else
				{
					return new BBCodeParser();
				}
		}
	}

	/**
	 * @desc Returns a unparser which will work in the language you chose.
	 * @return ContentUnparser The unparser to use to unparse you formatting
	 */
	public function get_unparser()
	{
		global $CONFIG;
		switch ($this->language_type)
		{
			case self::BBCODE_LANGUAGE:
				return new BBCodeUnparser();
			case self::TINYMCE_LANGUAGE:
				return new TinyMCEUnparser();
			default:
				if ($this->get_user_editor() == self::TINYMCE_LANGUAGE)
				{
					return new TinyMCEUnparser();
				}
				else
				{
					return new BBCodeUnparser();
				}
		}
	}

	/**
	 * @desc Returns a second parser which will work in the language you chose.
	 * @return ContentSecondParser The second parser to use just before displaying you formatted text
	 */
	public function get_second_parser()
	{
		return new ContentSecondParser();
	}

	/**
	 * @desc Returns an editor object which will display the editor corresponding to the language you chose.
	 * @return ContentParser The editor to use.
	 */
	public function get_editor()
	{
		switch ($this->language_type)
		{
			case self::BBCODE_LANGUAGE:
				return new BBCodeEditor();
			case self::TINYMCE_LANGUAGE:
				return new TinyMCEEditor();
			default:
				if ($this->get_user_editor() == self::TINYMCE_LANGUAGE)
				{
					return new TinyMCEEditor();
				}
				else
				{
					return new BBCodeEditor();
				}
		}
	}

	/**
	 * @desc Returns the name of the editor of the current user (chosen in its profile).
	 * @return string The editor used by the current user.
	 */
	public function get_user_editor()
	{
		global $User;
		return $User->get_attribute('user_editor');
	}

	/**
	 * @static
	 * @desc Returns the map of all the formatting types supported by the PHPBoost formatting editors and parsers.
	 * The keys of the map are the tags identifiers and the values the tags names.
	 * @return string[] The map
	 */
	public static function get_available_tags()
	{
		global $LANG;
		return array(
        	'b' => $LANG['format_bold'],
        	'i' => $LANG['format_italic'],
        	'u' => $LANG['format_underline'],
        	's' => $LANG['format_strike'],
        	'title' => $LANG['format_title'],
        	'style' => $LANG['format_style'],
        	'url' => $LANG['format_url'],
        	'img' => $LANG['format_img'],
        	'quote' => $LANG['format_quote'],
        	'hide' => $LANG['format_hide'],
        	'list' => $LANG['format_list'],
        	'color' => $LANG['format_color'],
        	'bgcolor' => $LANG['format_bgcolor'],
        	'font' => $LANG['format_font'],
        	'size' => $LANG['format_size'],
        	'align' => $LANG['format_align'],
        	'float' => $LANG['format_float'],
        	'sup' => $LANG['format_sup'], 
			'sub' => $LANG['format_sub'],
        	'indent' => $LANG['format_indent'],
        	'pre' => $LANG['format_pre'],
        	'table' => $LANG['format_table'],
        	'swf' => $LANG['format_flash'],
        	'movie' => $LANG['format_movie'],
        	'sound' => $LANG['format_sound'],
        	'code' => $LANG['format_code'],
        	'math' => $LANG['format_math'],
        	'anchor' => $LANG['format_anchor'],
        	'acronym' => $LANG['format_acronym'],
        	'block' => $LANG['format_block'],
			'fieldset' => $LANG['format_fieldset'],
        	'mail' => $LANG['format_mail'],
        	'line' => $LANG['format_line'],
        	'wikipedia' => $LANG['format_wikipedia'],
        	'html' => $LANG['format_html']
		);
	}
}

?>
