<?php
/*##################################################
 *                              forum_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright          : (C) 2007 Viarre R�gis
 *   email                : crowkait@phpboost.com
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
 
// Inclusion du fichier contenant la classe ModuleInterface
require_once('../includes/module_interface.class.php');
 
// Classe ForumInterface qui h�rite de la classe ModuleInterface
class ForumInterface extends ModuleInterface
{
	## Public Methods ##
    function ForumInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('forum');
    }
	
	//R�cup�re le lien vers la listes des messages du membre.
	function GetMembermsgLink($memberId)
    {
        return '../forum/membermsg.php?id=' . $memberId[0];
    }
	
	//R�cup�re le nom associ� au lien.
	function GetMembermsgName()
    {
        global $LANG;
		load_module_lang('forum'); //Chargement de la langue du module.
		
		return $LANG['forum'];
    }
	
	//R�cup�re l'image associ� au lien.
	function GetMembermsgImg()
    {
		return '../forum/forum_mini.png';
    }
    
    // Recherche
    function GetSearchForm()
    /**
     *  Renvoie le formulaire de recherche du forum
     */
    {
        $form  = '<fieldset>
        <legend>Recherche sur le Forum</legend>
        <dl>
            <dt><label for="search">Mots cl�s (4 caract�res minimum)</label></dt>
            <dd><label><input type="text" size="35" id="search" name="search" value=""  class="text" /></label></dd>
        </dl>

        <dl>
            <dt><label for="time">Date</label></dt>
            <dd><label> 
                <select id="time" name="time">
                    <option value="30000" selected="selected">Tout</option>
                    <option value="1">1 jour</option>
                    <option value="7">7 Jours</option>

                    <option value="15">15 Jours</option>
                    <option value="30">1 Mois</option>
                    <option value="180">6 Mois</option>
                    <option value="360">1 An</option>
                </select>
            </label></dd>
        </dl>

        <dl>
            <dt><label for="idcat">Cat�gorie</label></dt>
            <dd><label>
                <select name="idcat" id="idcat">
                    <option value="-1" selected="selected">Tout</option>
                    <option value="4">---- Support PHPBoost</option>
                    <option value="2">---------- Annonces</option>
                </select>

            </label></dd>
        </dl>
        <dl>
            <dt><label for="where">Options</label></dt>
            <dd>
                <label><input type="radio" name="where" id="where" value="contents" checked="checked" /> Contenu</label>
                <br />

                <label><input type="radio" name="where" value="title"  /> Titre</label>
                <br />
                <label><input type="radio" name="where" value="all"  /> Titre/Contenu</label>
            </dd>
        </dl>
        <dl>
            <dt><label for="colorate_result">Colorer les r�sultats</label></dt>

            <dd>
                <label><input type="checkbox" name="colorate_result" id="colorate_result" value="1" checked="checked" /></label>
            </dd>
        </dl>
    </fieldset>';
        return $form;
    }
    
    function GetSearchArgs()
    /**
     *  Renvoie la liste des arguments de la m�thode <GetSearchRequest>
     */
    {
        return Array('time', 'idcat', 'where', 'colorate_result');
    }
    
    function GetSearchRequest($args)
    /**
     *  Renvoie la requ�te de recherche dans le forum
     */
    {
        
    }
}
 
?>