{*

+-----------------------------------------------------------------------+
| Piwigo - external authentication plugin                               |
|                                 https://github.com/byo/piwigo-extauth |
+-----------------------------------------------------------------------+
| Copyright(C) 2012 Bartlomiej (byo) wiecki                             |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation; either version 2 of the License, or     |
| (at your option) any later version.                                   |
|                                                                       |
| This program is distributed in the hope that it will be useful, but   |
| WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
| General Public License for more details.                              |
|                                                                       |
| You should have received a copy of the GNU General Public License     |
| along with this program; if not, write to the Free Software           |
| Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 |
| USA.                                                                  |
+-----------------------------------------------------------------------+

*}
<form method="post" action="">
<fieldset>
	<legend>{'Associate accounts'|@translate}</legend>
	{if !count($extauthpending.eap_users)}
		<p>{'There are no account waiting for association. Ask your users to login using external method in order to associate them with the system.'|@translate}</p>
	{else}
		<table border="1" cellspacing="0" cellpadding="5" >
			<tr><th>{'Platform'|@translate}</th><th>{'Platform id'|@translate}</th><th>{'User'|@translate}</th></tr>
			{foreach from=$extauthpending.eap_users item=eu key=idx}
			{strip}
				<tr>
					{if isset( $eu.platformLink ) }
						<td><a target="_blank" href="{$eu.platformLink|@escape}">{$eu.platform|@escape}</a></td>
						<td><a target="_blank" href="{$eu.platformProfileLink|@escape}">{$eu.id|@escape}</a></td>
					{else}
						<td>{$eu.platform|@escape}</td>
						<td>{$eu.id|@escape}</td>
					{/if}
					<td>
						<input type="hidden" name="user[{$idx}][platform]" value="{$eu.platform|@escape}" />
						<input type="hidden" name="user[{$idx}][id]" value="{$eu.id|@escape}" />
						<select name="user[{$idx}][user_id]">
							<option value="-1">----</option>
							{foreach from=$extauthpending.users item=u}
								<option value="{$u.user_id|@escape}"{if $eu.suggestedUserId == $u.user_id} selected="selected"{/if}>{$u.user_name|@escape}</option>
							{/foreach}
						</select>
					</td>
				</tr>
			{/strip}
			{/foreach}
		</table>
	{/if}

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

