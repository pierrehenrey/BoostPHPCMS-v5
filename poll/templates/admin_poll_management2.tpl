		<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/calendar.js"></script>
		<script>
		<!--
		function check_form(){
			if(document.getElementById('question').value == "") {
				alert("{L_REQUIRE_QUESTION}");
				return false;
		    }
			if(document.getElementById('reponses').value == "") {
				alert("{L_REQUIRE_ANSWER}");
				return false;
		    }
				if(document.getElementById('type').value == "") {
				alert("{L_REQUIRE_ANSWER_TYPE}");
				return false;
		    }

			return true;
		}

		function add_field(i, i_max) 
		{
			var i2 = i + 1;

			if( document.getElementById('a'+i) )
				document.getElementById('a'+i).innerHTML = '<label><input type="text" name="a'+i+'" value="" /></label><br /><span id="a'+i2+'"></span>';
			if( document.getElementById('v'+i) )
				document.getElementById('v'+i).innerHTML = '<label><input type="number" min="0" max="1000" name="v'+i+'" value="" /> 0.0%</label><br /><span id="v'+i2+'"></span>';
			if( document.getElementById('s'+i) )
				document.getElementById('s'+i).innerHTML = (i < i_max) ? '<span id="s'+i2+'"><a href="javascript:add_field('+i2+', '+i_max+')"><i class="fa fa-plus"></i></a></span>' : '';
		}
		
		-->
		</script>

		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_POLL_MANAGEMENT}</li>
				<li>
					<a href="admin_poll.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll.php" class="quick-link">{L_POLL_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_poll_add.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_add.php" class="quick-link">{L_POLL_ADD}</a>
				</li>
				<li>
					<a href="admin_poll_config.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_config.php" class="quick-link">{L_POLL_CONFIG}</a>
				</li>
			</ul>
		</div> 
		
		<div id="admin-contents">
			# INCLUDE message_helper #
			
			<form action="admin_poll.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_POLL_MANAGEMENT}</legend>
					<div class="form-element">
						<label for="question">* {L_QUESTION}</label>
						<div class="form-field"><label><input type="text" maxlength="100" id="question" name="question" value="{QUESTIONS}"></label></div>
					</div>
					<div class="form-element">
						<label for="type">* {L_ANSWER_TYPE}</label>
						<div class="form-field">
							<label><input type="radio" name="type" id="type" {TYPE_UNIQUE} value="1"> {L_SINGLE}</label>
							<label><input type="radio" name="type" {TYPE_MULTIPLE} value="0"> {L_MULTIPLE}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="archive">* ${LangLoader::get_message('hidden', 'common')}</label>
						<div class="form-field">
							<label><input type="radio" name="archive" {ARCHIVES_ENABLED} value="1"> {L_YES}</label>
							<label><input type="radio" name="archive" {ARCHIVES_DISABLED} id="archive" value="0"> {L_NO}</label>
						</div>
					</div>
					<div class="form-element">
						<label>* {L_ANSWERS}</label>
						<div class="form-field">
							<table>
								<tbody>
									<tr>
										<td class="no-separator text-strong">
											{L_ANSWERS}
										</td>
										<td class="no-separator text-strong">
											{L_NUMBER_VOTE}
										</td>
									</tr>
									<tr>
										<td class="no-separator">
											# START answers #
											<label><input type="text" name="a{answers.ID}" value="{answers.ANSWER}" /></label><br />
											# END answers #
											<span id="a{MAX_ID}"></span>
										</td>
										<td class="no-separator">
											# START votes #
											<label><input type="number" min="0" max="1000" name="v{votes.ID}" value="{votes.VOTES}" /> {votes.PERCENT}</label><br />
											# END votes #
											<span id="v{MAX_ID}"></span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<script>
											<!--
												if( {MAX_ID} < 19 )
													document.write('<span id="s{MAX_ID}"><a href="javascript:add_field({MAX_ID}, 19)"><i class="fa fa-plus"></i></a></span>');
											-->
											</script>
											
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</fieldset>

				<fieldset>
					<legend>{L_DATE}</legend>
					<div class="form-element" class="overflow_visible">
						<label for="release_date">* {L_RELEASE_DATE}</label>
						<div class="form-field">
							<div onclick="document.getElementById('start_end_date').checked = true;">
								<label>
									<input type="radio" value="2" name="visible" id="start_end_date" {VISIBLE_WAITING}>
									<input type="text" size="11" maxlength="10" id="start" name="start" value="{START}"> 
									<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
										<div id="start_date" class="calendar-block" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">
										</div>
									</div>
									<a onclick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;" class="fa fa-calendar"></a>
									
									{L_UNTIL}&nbsp;
									
									<input type="text" size="11" maxlength="10" id="end" name="end" value="{END}"> 
									<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
										<div id="end_date" class="calendar-block" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">
										</div>
									</div>
									<a onclick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;" class="fa fa-calendar"></a>
								</label>
							</div>
							<label><input type="radio" value="1" name="visible" {VISIBLE_ENABLED} id="release_date"> {L_IMMEDIATE}</label>
							<label><input type="radio" value="0" name="visible" {VISIBLE_UNAPROB}> {L_UNAPROB}</label>
						</div>
					</div>
					<div class="form-element" class="overflow_visible">
						<label for="current_date">* {L_POLL_DATE}</label>
						<div class="form-field"><label>
							<input type="text" size="11" maxlength="10" id="current_date" name="current_date" value="{CURRENT_DATE}"> 
							<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
								<div id="current" class="calendar-block" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">
								</div>
							</div>
							<a onclick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;" class="fa fa-calendar"></a>
							{L_AT}
							<input type="text" size="2" maxlength="2" name="hour" value="{HOUR}" /> h <input type="text" size="2" maxlength="2" name="min" value="{MIN}">
						</label></div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<input type="hidden" name="id" value="{IDPOLL}">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
