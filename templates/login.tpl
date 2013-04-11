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
{combine_css path="plugins/external_auth/css/style.css"}

<dt>{'Connect with:'|@translate}</dt>
<dd class="extauth">
{foreach from=$block->data.platforms key=n item=pl}
{strip}
	<p class="{$n|escape}">
		<a href="{$pl.loginUrl|escape}">
			<span class="inner">
				{$pl.name|escape}
			</span>
		</a>
	</p>
{/strip}
{/foreach}
</dd>
