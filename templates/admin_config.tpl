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

