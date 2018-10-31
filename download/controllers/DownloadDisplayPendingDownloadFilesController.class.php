<?php
/*##################################################
 *                               DownloadDisplayPendingDownloadFilesController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
 
class DownloadDisplayPendingDownloadFilesController extends ModuleController
{
	private $tpl;
	private $lang;
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response($request);
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'download');
		$this->tpl = new FileTemplate('download/DownloadDisplaySeveralDownloadFilesController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = DownloadConfig::load();
	}
	
	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$authorized_categories = DownloadService::get_authorized_categories(Category::ROOT_CATEGORY);
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', DownloadFile::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		
		$condition = 'WHERE id_category IN :authorized_categories
		' . (!DownloadAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (approbation_type = 0 OR (approbation_type = 2 AND (start_date > :timestamp_now OR (end_date != 0 AND end_date < :timestamp_now))))';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);
		
		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($mode), $page);
		
		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(DownloadFile::ASC, DownloadFile::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());
		
		if (in_array($field, array(DownloadFile::SORT_FIELDS_URL_VALUES[DownloadFile::SORT_ALPHABETIC], DownloadFile::SORT_FIELDS_URL_VALUES[DownloadFile::SORT_AUTHOR], DownloadFile::SORT_FIELDS_URL_VALUES[DownloadFile::SORT_DATE])))
			$sort_field = array_search($field, DownloadFile::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = DownloadFile::SORT_DATE;
		
		$result = PersistenceContext::get_querier()->select('SELECT download.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM '. DownloadSetup::$download_table .' download
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = download.id AND com.module_id = \'download\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));
		
		$number_columns_display_per_line = $this->config->get_columns_number_per_line();

		$this->tpl->put_all(array(
			'C_FILES' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_FILE' => $result->get_rows_count() > 1,
			'C_PENDING' => true,
			'C_CATEGORY_DISPLAYED_SUMMARY' => $this->config->is_category_displayed_summary(),
			'C_CATEGORY_DISPLAYED_TABLE' => $this->config->is_category_displayed_table(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'NUMBER_COLUMNS' => $number_columns_display_per_line,
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('download'),
			'C_NOTATION_ENABLED' => $content_management_config->module_notation_is_enabled('download'),
			'C_AUTHOR_DISPLAYED' => $this->config->is_author_displayed(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'TABLE_COLSPAN' => 4 + (int)$comments_config->module_comments_is_enabled('download') + (int)$content_management_config->module_notation_is_enabled('download')
		));
		
		while ($row = $result->fetch())
		{
			$downloadfile = new DownloadFile();
			$downloadfile->set_properties($row);
			
			$keywords = $downloadfile->get_keywords();
			$has_keywords = count($keywords) > 0;
			
			$this->tpl->assign_block_vars('downloadfiles', array_merge($downloadfile->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));
			
			if ($has_keywords)
				$this->build_keywords_view($keywords);
		}
		$result->dispose();
		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));
	}
	
	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');
		
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');
		
		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, 
			array(
				new FormFieldSelectChoiceOption($common_lang['form.date.creation'], DownloadFile::SORT_FIELDS_URL_VALUES[DownloadFile::SORT_DATE]),
				new FormFieldSelectChoiceOption($common_lang['form.name'], DownloadFile::SORT_FIELDS_URL_VALUES[DownloadFile::SORT_ALPHABETIC]),
				new FormFieldSelectChoiceOption($common_lang['author'], DownloadFile::SORT_FIELDS_URL_VALUES[DownloadFile::SORT_AUTHOR])
			), 
			array('events' => array('change' => 'document.location = "'. DownloadUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . DownloadUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->tpl->put('SORT_FORM', $form->display());
	}
	
	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$downloadfiles_number = DownloadService::count($condition, $parameters);
		
		$pagination = new ModulePagination($page, $downloadfiles_number, (int)DownloadConfig::load()->get_items_number_per_page());
		$pagination->set_url(DownloadUrlBuilder::display_pending($field, $mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);
		
		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('downloadfiles.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => DownloadUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}
	
	private function check_authorizations()
	{
		if (!(DownloadAuthorizationsService::check_authorizations()->write() || DownloadAuthorizationsService::check_authorizations()->contribution() || DownloadAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', DownloadFile::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['download.pending'], $this->lang['module_title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['download.seo.description.pending'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::display_pending($sort_field, $sort_mode, $page));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], DownloadUrlBuilder::home());
		$breadcrumb->add($this->lang['download.pending'], DownloadUrlBuilder::display_pending($sort_field, $sort_mode, $page));
		
		return $response;
	}
}
?>