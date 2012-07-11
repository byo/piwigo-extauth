<form method="post" action="">
<fieldset>
	<legend>{'Associate accounts'|@translate}</legend>
	<table border="1" cellspacing="0" cellpadding="5" >
		<tr><th>{'Platform'|@translate}</th><th>{'Platform id'|@translate}</th><th>{'User'|@translate}</th></tr>
		{foreach from=$extauthpending.eap_users item=eu}
		{strip}
			<tr>
				{if $eu.platform == 'FACEBOOK'}
					<td><a target="_blank" href="http://www.facebook.com">Facebook</a></td>
					<td><a target="_blank" href="http://www.facebook.com/{$eu.id|escape}">{$eu.id|escape}</a></td>
				{else}
					<td>{$eu.platform|escape}</td>
					<td>{$eu.id|escape}</td>
				{/if}
				<td>
					<input type="hidden" name="user[{$smarty.foreach.eu.index}][platform]" value="{$eu.platform|escape}" />
					<input type="hidden" name="user[{$smarty.foreach.eu.index}][id]" value="{$eu.id|escape}" />
					<select name="user[{$smarty.foreach.eu.index}][user_id]">
						<option value="-1">----</option>
						{foreach from=$extauthpending.users item=u}
							<option value="{$u.user_id|escape}">{$u.user_name|escape}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		{/strip}
		{/foreach}
	</table>

</fieldset>
  <p>
    <input type="submit" value="{'Submit'|@translate}" name="submit">
  </p>
</form>
{literal}
<script>
	$(function(){
	});
</script>
{/literal}

