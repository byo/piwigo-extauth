{if isset($MENUBAR)}{$MENUBAR}{/if}
<div id="content" class="content{if isset($MENUBAR)} contentWithMenu{/if}">
  <div class="titlePage">
    <ul class="categoryActions">
    </ul>
    <h2><a href="{$U_HOME}">{'Home'|@translate}</a>{$LEVEL_SEPARATOR}{'Approval pending'|@translate}</h2>
  </div>
  
  {include file='infos_errors.tpl'}
  
  <div id="extAuthPendingContent">
	  <h2>{'Thank you for stepping by. Administrators will approve your account soon.'|@translate}</h2>
  </div>
</div>




