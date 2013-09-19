{*

+-----------------------------------------------------------------------+
| Piwigo - external authentication plugin                               |
|                                 https://github.com/byo/piwigo-extauth |
+-----------------------------------------------------------------------+
| Copyright(C) 2012-2013 Bartlomiej (byo) Swiecki                       |
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
{foreach from=$extauth.platforms key=n item=pl}
	<fieldset>
	{strip}
		<legend>
			<input class="enabler" type="checkbox" name="{$n}_enabled" id="{$n}_enabled" value="1" {if $pl.enabled}checked="checked"{/if} />
			&nbsp;
			{'Enable'|@translate} {$pl.name}
		</legend>
	{/strip}
	<div class="details" {if !$pl.enabled}style="display:none;"{/if}>
	<input type="hidden" name="{$n}_fakeSecret" value="{$pl.fakeSecret|@escape}" />
	<table>
	<tr><td>{'Id:'|@translate}</td><td><input type="text" name="{$n}_id" value="{$pl.id|@escape}" /></td></tr>
	<tr><td>{'Secret:'|@translate}</td><td><input type="password" name="{$n}_secret" value="{$pl.fakeSecret|@escape}" /></td></tr>
	</table>
	</div>
	</fieldset>
{/foreach}
  <p>
    <input type="submit" value="{'Submit'|@translate}" name="submit">
  </p>
</form>
{literal}
<script>
	$(function(){
		$(".enabler").change(function(){
			var val = $(this).is(":checked");
			var details = $(this).parent().parent().find('.details');
			if ( val ) {
				details.slideDown(200);
			} else {
				details.slideUp(200);
			}
		}).trigger('change');
	});
</script>
{/literal}

