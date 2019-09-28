		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" aria-label="{L_QUICK_LINKS}">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_QUICK_LINKS}
			</a>
			<ul>
				<li>
					<a href="{PATH_TO_ROOT}/admin/admin_alerts.php" class="quick-link">{L_ADMINISTRATOR_ALERTS}</a>
				</li>
				<li>
					<a href="${relative_url(AdminMembersUrlBuilder::management())}" class="quick-link">{L_ACTION_USERS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/menus/menus.php" class="quick-link">{L_ACTION_MENUS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}" class="quick-link">{L_ACTION_MODULES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}" class="quick-link">{L_ACTION_THEMES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}" class="quick-link">{L_ACTION_LANGS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="{PATH_TO_ROOT}/admin/updates/updates.php" class="quick-link">{L_WEBSITE_UPDATES}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">

			<div class="content cell-flex cell-flex-3">
				<div class="cell cell-2-3">
					<div class="cell-body">
						<div class="index-logo hidden-small-screens" # IF C_HEADER_LOGO #style="background-image: url({HEADER_LOGO});"# ENDIF #></div>
						<div class="welcome-desc">
							<h2>{L_WELCOME_TITLE}</h2>
							<p>{L_WELCOME_DESC}</p>
						</div>
					</div>
				</div>
				<div class="cell">
					<div class="cell-title">
						<h2><i class="fa fa-bell" aria-hidden="true"></i> {L_ADMIN_ALERTS}</h2>
					</div>
					<div class="cell-body">
						# IF C_UNREAD_ALERTS #
							<div class="message-helper warning">{L_UNREAD_ALERT}</div>
						# ELSE #
							<div class="message-helper success">{L_NO_UNREAD_ALERT}</div>
						# ENDIF #
						# IF C_UNREAD_ALERTS #
						<p class="smaller center">
							<a href="admin_alerts.php">{L_DISPLAY_ALL_ALERTS}</a>
						</p>
						# ENDIF #
					</div>
				</div>
			</div>

			<div class="content quick-access hidden-small-screens">
				<h2><i class="fa fa-angle-double-right" aria-hidden="true"></i> {L_QUICK_ACCESS}</h2>
				<div class="cell-flex cell-flex-3">
					<div class="cell">
						<div class="cell-title">
							<h3><i class="fa fa-fw fa-cogs" aria-hidden="true"></i> {L_SITE_MANAGEMENT}</h3>
						</div>
						<div class="cell-list">
							<ul>
								<li><a href="${relative_url(AdminConfigUrlBuilder::general_config())}">{L_GENERAL_CONFIG}</a></li>
								<li><a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}">{L_EMPTY_CACHE}</a></li>
								# IF C_MODULE_DATABASE_INSTALLED #
								<li><a href="{U_SAVE_DATABASE}">{L_SAVE_DATABASE}</a></li>
								# ENDIF #
							</ul>
						</div>
					</div>
					<div class="cell">
						<div class="cell-title">
							<h3><i class="fa fa-fw fa-image" aria-hidden="true"></i> {L_CUSTOMIZE_SITE}</h3>
						</div>
						<div class="cell-list">
							<ul>
								<li><a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">{L_ADD_TEMPLATE}</a></li>
								<li><a href="{PATH_TO_ROOT}/admin/menus">{L_MENUS_MANAGEMENT}</a></li>
								# IF C_MODULE_CUSTOMIZATION_INSTALLED #
								<li><a href="{U_EDIT_CSS_FILES}">{L_CUSTOMIZE_TEMPLATE}</a></li>
								# ENDIF #
							</ul>
						</div>
					</div>
					<div class="cell">
						<div class="cell-title">
							<h3><i class="fa fa-fw fa-plus" aria-hidden="true"></i> {L_ADD_CONTENT}</h3	>
						</div>
						<div class="cell-list">
							<ul>
								<li><a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">{L_MODULES_MANAGEMENT}</a></li>
								# IF C_MODULE_ARTICLES_INSTALLED #
								<li><a href="{U_ADD_ARTICLE}">{L_ADD_ARTICLES}</a></li>
								# ENDIF #
								# IF C_MODULE_NEWS_INSTALLED #
								<li><a href="{U_ADD_NEWS}">{L_ADD_NEWS}</a></li>
								# ENDIF #
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="content cell-flex cell-flex-2">
				<div class="cell dashboard-user-online">
					<div class="cell-title">
						<h2><i class="fa fa-user" aria-hidden="true"></i> {L_USER_ONLINE}</h2>
					</div>
					<div class="cell-table">
						<table class="table">
							<thead>
								<tr>
									<th>{L_USER_ONLINE}</th>
									<th>{L_USER_IP}</th>
									<th>{L_LOCALISATION}</th>
									<th>{L_LAST_UPDATE}</th>
								</tr>
							</thead>
							<tbody>
								# START user #
								<tr>
									<td>
									# IF user.C_ROBOT #
										<span class="{user.LEVEL_CLASS}">{user.PSEUDO}</span>
									# ELSE #
										# IF user.C_VISITOR #
										{user.PSEUDO}
										# ELSE #
										<a href="{user.U_PROFILE}" class="{user.LEVEL_CLASS}" # IF user.C_GROUP_COLOR # style="color:{user.GROUP_COLOR}" # ENDIF #>{user.PSEUDO}</a>
										# ENDIF #
									# ENDIF #
									</td>
									<td>{user.USER_IP}</td>
									<td>{user.WHERE}</td>
									<td>{user.TIME}</td>
								</tr>
								# END user #
							</tbody>
						</table>
					</div>
				</div>

				<div class="cell dashboard-comments">
					<div class="cell-title">
						<h2><i class="fa fa-comment" aria-hidden="true"></i> {L_LAST_COMMENTS}</h2>
					</div>
					<div class="cell-list">
						<ul>
							# START comments_list #
								<li>
									<a href="{comments_list.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete" aria-hidden="true" aria-label=""></i></a>
									<a href="{comments_list.U_LINK}" aria-label="${LangLoader::get_message('pm_conversation_link', 'main')}">
										<i class="far fa-hand-point-right" aria-hidden="true"></i>
									</a>
									<span class="smaller">{L_BY} # IF comments_list.C_VISITOR #{comments_list.PSEUDO}# ELSE #<a href="{comments_list.U_PROFILE}" class="{comments_list.LEVEL_CLASS}" # IF comments_list.C_GROUP_COLOR # style="color:{comments_list.GROUP_COLOR}" # ENDIF #>{comments_list.PSEUDO}</a># ENDIF #</span> :
									{comments_list.CONTENT}
								</li>
							# END comments_list #
							# IF C_NO_COM #
								<li>
									<p class="center"><em>{L_NO_COMMENT}</em></p>
								</li>
							# ELSE #
								<li>
									<p class="smaller center"><a href="${relative_url(UserUrlBuilder::comments())}">{L_VIEW_ALL_COMMENTS}</a></p>
								</li>
							# ENDIF #
						</ul>

					</div>
				</div>

				<div class="cell dashboard-advices">
					<div class="cell-body">
						# INCLUDE ADVISES #
					</div>
				</div>

				<div class="cell dashboard-writting-pad">
					<form action="admin_index.php" method="post">
						<div class="cell-title">
							<h2><i class="fa fa-edit" aria-hidden="true"></i> {L_WRITING_PAD}</h2>
						</div>
						<div class="cell-body">
							<div class="fieldset-inset">
								<div class="form-element full-field">
									<textarea id="writing_pad_content" name="writing_pad_content">{WRITING_PAD_CONTENT}</textarea>
								</div>
								<p class="center">
									<button type="submit" class="submit" name="writingpad" value="true">{L_UPDATE}</button>
									<button type="reset" value="true">{L_RESET}</button>
									<input type="hidden" name="token" value="{TOKEN}">
								</p>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="spacer"></div>
		</div><!-- admin-contents -->
