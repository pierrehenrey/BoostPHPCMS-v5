		# INCLUDE forum_top #
		
		<div class="module-position">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span><em>{DESC}</em></span> &raquo; <a href="">{L_ALERT}</a></div>
			<div class="module-contents">

			# START alert_form #
				<script type='text/javascript'>
				<!--
				function check_form_alert(){
					if(document.getElementById('contents').value == "") {
						alert("{L_REQUIRE_TEXT}");
						return false;
					}
					if(document.getElementById('title').value == "") {
						alert("{L_REQUIRE_TITLE}");
						return false;
					}
					return true;
				}
				-->
				</script>
				
				<form method="post" action="alert.php" onsubmit="javascript:return check_form_alert();">
					<fieldset>
						<legend>{L_ALERT}</legend>
						
						<div id="id-message-helper" class="notice" style="width:80%;">{L_ALERT_EXPLAIN}: <a href="{alert_form.U_TOPIC}">{alert_form.TITLE}</a></div>
						<div class="form-element">
							<label for="title">{L_ALERT_TITLE}</label>
							<div class="form-field">
								<input type="text" name="title" id="title" size="50">
							</div>
						</div>
						<div class="form-element-textarea">
							<label for="contents"> *{L_ALERT_CONTENTS}</label>
							{KERNEL_EDITOR}
							<textarea style="width:70%" rows="15" cols="40" id="contents" name="contents"></textarea> 
							<input type="hidden" name="id" value="{alert_form.ID_ALERT}">
						</div>
					</fieldset>

					<fieldset class="fieldset-submit">
							<button type="submit" name="edit_msg" value="true" class="submit">{L_SUBMIT}</button>
							<button onclick="XMLHttpRequest_preview();" type="button">{L_PREVIEW}</button>
							<button type="reset" value="true">{L_RESET}</button>
							<input type="hidden" name="token" value="{TOKEN}">
					</fieldset>
				</form>
				<br />
				# END alert_form #


				# START alert_confirm #
				<fieldset>
					<legend>{L_ALERT}</legend>
					<div class="center">
						<br /><br />
						{alert_confirm.MSG}
						<br /><br />
						<a href="{URL_TOPIC}">{L_BACK_TOPIC}</a>

				</fieldset>
				# END alert_confirm #
			</div>
			<div class="module-bottom-l"></div>
			<div class="module-bottom-r"></div>
			<div class="module-bottom">&bull; {U_FORUM_CAT} &raquo; {U_TITLE_T} <span><em>{DESC}</em></span> &raquo; <a href="">{L_ALERT}</a></div>
		</div>
		
		# INCLUDE forum_bottom #
		