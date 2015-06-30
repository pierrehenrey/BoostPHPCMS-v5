<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : August 30, 2007
 *   copyright            : (C) 2007 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
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

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once('../gallery/gallery_begin.php');
require_once('../kernel/header_no_display.php');

if (AppContext::get_current_user()->is_readonly())
	exit;

$config = GalleryConfig::load();

//Notation.
if (!empty($_GET['increment_view']))
{
	$categories = GalleryService::get_categories_manager()->get_categories_cache()->get_categories();
	$g_idpics = retrieve(GET, 'id', 0);
	$g_idcat = retrieve(GET, 'cat', 0);
	if (empty($g_idpics) || (!empty($g_idcat) && !isset($categories[$g_idcat])))
		exit;
	
	//Niveau d'autorisation de la cat�gorie
	if (!GalleryAuthorizationsService::check_authorizations($g_idcat)->read())
		exit;

	//Mise � jour du nombre de vues.
	PersistenceContext::get_querier()->inject("UPDATE " . GallerySetup::$gallery_table . " SET views = views + 1 WHERE idcat = :idcat AND id = :id", array('idcat' => $g_idcat, 'id' => $g_idpics));
}
else
{	
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	if (!empty($_GET['rename_pics'])) //Renomme une image.
	{
		$id_file = retrieve(POST, 'id_file', 0);
		$id_cat = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'idcat', 'WHERE id = :id', array('id' => $id_file));
		
		if (GalleryAuthorizationsService::check_authorizations($id_cat)->moderation()) //Modo
		{
			//Initialisation  de la class de gestion des fichiers.
			include_once(PATH_TO_ROOT .'/gallery/Gallery.class.php');
			$Gallery = new Gallery;

			$name = !empty($_POST['name']) ? TextHelper::strprotect(utf8_decode($_POST['name'])) : '';
			$previous_name = !empty($_POST['previous_name']) ? TextHelper::strprotect(utf8_decode($_POST['previous_name'])) : '';
			
			if (!empty($id_file))
				echo $Gallery->Rename_pics($id_file, $name, $previous_name);
			else 
				echo -1;
		}
	}
	elseif (!empty($_GET['aprob_pics']))
	{
		$id_file = retrieve(POST, 'id_file', 0);
		$id_cat = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'idcat', 'WHERE id = :id', array('id' => $id_file));
		
		if (GalleryAuthorizationsService::check_authorizations($id_cat)->moderation()) //Modo
		{
			$Gallery = new Gallery();
			
			if (!empty($id_file))
			{
				echo $Gallery->Aprob_pics($id_file);
				//R�g�n�ration du cache des photos al�atoires.
				GalleryMiniMenuCache::invalidate();
			}
			else 
				echo 0;
		}
	}
}

?>