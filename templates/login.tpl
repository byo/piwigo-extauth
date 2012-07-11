{combine_css path="plugins/external_auth/css/style.css"}

<dt>{'Connect with:'|@translate}</dt>
<dd class="extauth">
{strip}
	{if $block->data.fbEnabled }
		<p class="facebook">
			<a href="{$block->data.fbLoginUrl|escape}">
				<span class="inner">
					Facebook
				</span>
			</a>
		</p>
	{/if}
{/strip}
</dd>
