# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	# INCLUDE MSG #
	<table id="table">
		<caption>{@modules.modules_available}</caption>
		# IF C_MODULES_AVAILABLE #
		<thead>
			<tr> 
				<th>{@modules.name}</th>
				<th>{@modules.description}</th>
				<th>${LangLoader::get_message('enable', 'common')}</th>
				<th>{@modules.install_module}</th>
			</tr>
		</thead>
		<tbody>
			# START available #
			<tr>
				<td>
					<img src="{PATH_TO_ROOT}/{available.ICON}/{available.ICON}.png" alt="{available.NAME}" />
					<span class="text-strong">{available.NAME}</span>
					<em>({available.VERSION})</em>
				</td>
				<td class="left">
					<span class="text-strong">{@modules.author} :</span> {available.AUTHOR} {available.AUTHOR_WEBSITE}<br />
					<span class="text-strong">{@modules.description} :</span> {available.DESCRIPTION}<br />
					<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {available.COMPATIBILITY}<br />
				</td>
				<td class="input-radio">
					<label><input type="radio" name="activated-{available.ID}" value="1" checked="checked"> ${LangLoader::get_message('yes', 'common')}</label>
					<label><input type="radio" name="activated-{available.ID}" value="0"> ${LangLoader::get_message('no', 'common')}</label>
				</td>
				<td>
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" class="submit" name="add-{available.ID}" value="true">{@modules.install_module}</button>
				</td>
			</tr>
			# END available #
		</tbody>
	</table>
		# ELSE #
	</table>
	<div class="notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
</form>