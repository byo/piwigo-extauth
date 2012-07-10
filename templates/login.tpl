{combine_css path="plugins/external_auth/css/style.css"}

<dt>{'Connect with:'|@translate}</dt>
<dd class="extauth">
{strip}
	<p class="facebook">
		<a href="{$block->data.fb_login_url}">
			<span class="inner">
				Facebook
			</span>
		</a>
	</p>
{/strip}
</dd>
