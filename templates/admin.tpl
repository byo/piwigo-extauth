<!-- Show the title of the plugin -->
<div class="titlePage">
  <h2>{'External authentication methods plugin'|@translate}</h2>
  {if $extauth.updated}<h3>Updated</h2>{/if}
</div>

<form method="post" action="">
<fieldset>
{strip}
	<legend>
		<input type="checkbox" name="fbEnabled" id="fbEnabled" value="1" {if $extauth.fbEnabled}checked="checked"{/if} />
		{'Login using facebook'|@translate}
	</legend>
{/strip}
<div id="fbDetails" {if !$extauth.fbEnabled}style="display:none;"{/if}>
<p>Facebook app Id: <input type="text" name="fbAppId" value="{$extauth.fbAppId|escape}" /></p>
<p>Facebook secret: <input type="text" name="fbSecret" value="{$extauth.fbSecret|escape}" /></p>
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

