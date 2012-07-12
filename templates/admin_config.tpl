
<form method="post" action="">
<fieldset>
{strip}
	<legend>
		<input type="checkbox" name="fbEnabled" id="fbEnabled" value="1" {if $extauth.fbEnabled}checked="checked"{/if} />
		&nbsp;
		{'Enable Facebook Connect'|@translate}
	</legend>
{/strip}
<div id="fbDetails" {if !$extauth.fbEnabled}style="display:none;"{/if}>
<table>
<tr><td>{'App Id:'|@translate}</td><td><input type="text" name="fbAppId" value="{$extauth.fbAppId|@escape}" /></td></tr>
<tr><td>{'Secret:'|@translate}</td><td><input type="text" name="fbSecret" value="{$extauth.fbSecret|@escape}" /></td></tr>
</table>
</div>
</fieldset>
  <p>
    <input type="submit" value="{'Submit'|@translate}" name="submit">
  </p>
</form>
{literal}
<script>
	$(function(){
		$("#fbEnabled").change(function(){
			var val = $(this).is(":checked");
			if ( val ) {
				$("#fbDetails").slideDown(200);
			} else {
				$("#fbDetails").slideUp(200);
			}
		}).trigger('change');
	});
</script>
{/literal}

