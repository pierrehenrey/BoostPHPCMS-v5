<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 13
 * @since       PHPBoost 4.0 - 2013 02 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsItemController extends ModuleController
{
	private $lang;
	private $view;

	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->count_views_number($request);

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->view = new FileTemplate('news/NewsItemController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function get_news()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = NewsService::get_news('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->item = new News();
		}
		return $this->item;
	}

	private function count_views_number(HTTPRequestCustom $request)
	{
		if (!$this->item->is_visible())
		{
			$this->view->put('NOT_VISIBLE_MESSAGE', MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING));
		}
		else
		{
			if ($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), NewsUrlBuilder::display_item($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel()))
			{
				$this->item->set_views_number($this->item->get_views_number() + 1);
				NewsService::update_views_number($this->item);
			}
		}
	}

	private function build_view()
	{
		$comments_config = CommentsConfig::load();
		$category = $this->item->get_category();

		$this->view->put_all(array_merge($this->item->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('news'),
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		if ($comments_config->module_comments_is_enabled('news'))
		{
			$comments_topic = new NewsCommentsTopic($this->item);
			$comments_topic->set_id_in_module($this->item->get_id());
			$comments_topic->set_url(NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));

			$this->view->put_all(array(
				'COMMENTS' => $comments_topic->display()
			));
		}

		foreach ($this->item->get_sources() as $name => $url)
		{
			$this->view->assign_block_vars('sources', $this->item->get_array_tpl_source_vars($name));
		}

		$this->build_keywords_view($this->item);
		$this->build_suggested_news($this->item);
		$this->build_navigation_links($this->item);
	}

	private function build_keywords_view()
	{
		$keywords = $this->item->get_keywords();
		$keywords_number = count($keywords);
		$this->view->put('C_KEYWORDS', $keywords_number > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $keywords_number,
				'NAME' => $keyword->get_name(),
				'URL' => NewsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function build_suggested_news()
	{
		$now = new Date();

		$result = PersistenceContext::get_querier()->select('SELECT
			id, title, id_category, rewrited_title, thumbnail_url,
			(2 * FT_SEARCH_RELEVANCE(title, :search_content) + FT_SEARCH_RELEVANCE(content, :search_content) / 3) AS relevance
		FROM ' . NewsSetup::$news_table . '
		WHERE (FT_SEARCH(title, :search_content) OR FT_SEARCH(content, :search_content)) AND id <> :excluded_id
		AND (publication = 1 OR (publication = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))
		ORDER BY relevance DESC LIMIT 0, 10', array(
			'excluded_id' => $this->item->get_id(),
			'search_content' => $this->item->get_title() .','. $this->item->get_contents(),
			'timestamp_now' => $now->get_timestamp()
		));

		$this->view->put('C_SUGGESTED_NEWS', ($result->get_rows_count() > 0 && NewsConfig::load()->get_news_suggestions_enabled()));

		while ($row = $result->fetch())
		{
			$this->view->assign_block_vars('suggested', array(
				'CATEGORY_NAME' => CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_name(),
				'U_CATEGORY' =>  NewsUrlBuilder::display_category($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name())->rel(),
				'TITLE' => $row['title'],
				'U_ITEM' => NewsUrlBuilder::display_item($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel(),
				'U_THUMBNAIL' => !empty($row['thumbnail_url']) ? Url::to_rel($row['thumbnail_url']) : $this->item->get_default_thumbnail()->rel()
			));
		}
		$result->dispose();
	}

	private function build_navigation_links()
	{
		$now = new Date();
		$timestamp_news = $this->item->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, \'PREVIOUS\' as type
		FROM '. NewsSetup::$news_table .'
		WHERE (publication = 1 OR (publication = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date < :timestamp_news AND id_category IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, title, id_category, rewrited_title, thumbnail_url, \'NEXT\' as type
		FROM '. NewsSetup::$news_table .'
		WHERE (publication = 1 OR (publication = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date > :timestamp_news AND id_category IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp_news' => $timestamp_news,
			'authorized_categories' => array($this->item->get_id_category())
		));

		while ($row = $result->fetch())
		{
			$this->view->put_all(array(
				'C_RELATED_LINKS' => true,
				'C_'. $row['type'] .'_ITEM' => true,
				$row['type'] . '_ITEM' => $row['title'],
				'U_'. $row['type'] .'_ITEM' => NewsUrlBuilder::display_item($row['id_category'], CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel(),
				'U_'. $row['type'] .'_THUMBNAIL' => !empty($row['thumbnail_url']) ? Url::to_rel($row['thumbnail_url']) : $this->item->get_default_thumbnail()->rel()
			));
		}
		$result->dispose();
	}

	private function check_authorizations()
	{
		$item = $this->get_news();
		$current_user = AppContext::get_current_user();
		$not_authorized = !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation() && !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->write() && (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->contribution() || $this->item->get_author_user()->get_id() != $current_user->get_id());

		switch ($this->item->get_publication()) {
			case News::APPROVAL_NOW:
				if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->read())
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case News::NOT_APPROVAL:
				if ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			case News::APPROVAL_DATE:
				if (!$this->item->is_visible() && ($not_authorized || ($current_user->get_id() == User::VISITOR_LEVEL)))
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
					DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			break;
		}
	}

	private function generate_response()
	{
		$category = $this->get_news()->get_category();
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_news()->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->get_news()->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->get_news()->get_id(), $this->get_news()->get_rewrited_title()));

		if ($this->get_news()->has_thumbnail())
			$graphical_environment->get_seo_meta_data()->set_picture_url($this->get_news()->get_thumbnail());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], NewsUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_news()->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), NewsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->get_news()->get_title(), NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->get_news()->get_id(), $this->get_news()->get_rewrited_title()));

		return $response;
	}
}
?>
