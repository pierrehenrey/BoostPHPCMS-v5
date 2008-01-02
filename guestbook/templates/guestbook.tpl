		<script type="text/javascript">
		<!--
		function check_form_or(){
			if(document.getElementById('guestbook_contents').value == "") {
				alert("{L_ALERT_TEXT}");
				return false;
		    }
			return true;
		}

		function Confirm() {
		return confirm("{L_DELETE_MSG}");
		}
					
		-->
		</script>

		<form action="guestbook.php{UPDATE}" method="post" onsubmit="return check_form_or();" class="fieldset_mini">
			<fieldset>
				<legend>{L_ADD_MSG}{L_UPDATE_MSG}</legend>
				<p>{L_REQUIRE}</p>
				
				# START visible_guestbook #
				<dl>
					<dt><label for="guestbook_pseudo">* {L_PSEUDO}</label></dt>
					<dd><label><input type="text" size="25" maxlength="25" name="guestbook_pseudo" id="guestbook_pseudo" value="{visible_guestbook.PSEUDO}" class="text" /></label></dd>
				</dl>
				# END visible_guestbook #			
				
				<label for="guestbook_contents">* {L_MESSAGE}</label>
				# INCLUDE handle_bbcode #
				<label><textarea rows="10" cols="47" id="guestbook_contents" name="guestbook_contents">{CONTENTS}</textarea></label>
				<p>
					<strong>{L_FORBIDDEN_TAGS}</strong> {DISPLAY_FORBIDDEN_TAGS}
				</p>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				# START hidden_guestbook #
					<input type="hidden" size="25" maxlength="25" name="guestbook_pseudo" value="{hidden_guestbook.PSEUDO}" class="text" />
				# END hidden_guestbook #
				
				<input type="hidden" name="guestbook_contents_ftags" id="guestbook_contents_ftags" value="{FORBIDDEN_TAGS}" />
				<input type="submit" name="guestbook" value="{L_SUBMIT}" class="submit" />
				
				<input value="{L_PREVIEW}" type="submit" name="previs" id="previs_guestbook" class="submit" />
				<script type="text/javascript">
				<!--				
				document.getElementById('previs_guestbook').style.display = 'none';
				document.write('<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" type="button" class="submit" />');
				-->
				</script>
				
				<input type="reset" value="{L_RESET}" class="reset" />			
			</fieldset>	
		</form>

		<br />
		# START error_handler #
		<span id="errorh"></span>
		<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
			<br />	
		</div>
		<br />		
		# END error_handler #
		
		<div class="msg_position">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top" style="text-align:center;">{PAGINATION}&nbsp;</div>	
		</div>
		# START guestbook #
		<div class="msg_position">
			<div class="msg_container">
				<span id="m{guestbook.ID}"></span>
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{guestbook.USER_ONLINE} {guestbook.USER_PSEUDO}
					</div>
					<div style="float:left;">&nbsp;&nbsp;<a href="{guestbook.U_ANCHOR}"><img src="../templates/{THEME}/images/ancre.png" alt="{guestbook.ID}" /></a> {guestbook.DATE}</div>
					<div style="float:right;">{guestbook.EDIT}{guestbook.DEL}&nbsp;&nbsp;</div>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{guestbook.USER_RANK}</p>
						<p style="text-align:center;">{guestbook.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{guestbook.USER_AVATAR}</p>
						<p style="text-align:center;">{guestbook.USER_GROUP}</p>
						{guestbook.USER_SEX}
						{guestbook.USER_DATE}<br />
						{guestbook.USER_MSG}<br />
						{guestbook.USER_LOCAL}
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{guestbook.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				<div class="msg_sign_overflow">
					{guestbook.USER_SIGN}	
				</div>				
				<hr />
				<div style="float:right;font-size:10px;">
					{guestbook.WARNING} {guestbook.PUNISHMENT}
				</div>&nbsp;
			</div>	
		</div>				
		# END guestbook #		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">{PAGINATION}&nbsp;</div>
		</div>
		