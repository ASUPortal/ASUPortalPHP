<div style="display: {if $wap_mode}inline{else}none{/if}; float: left; margin-right: 5px; " id="wap_main_menu">
    <span>Разделы сайта:</span>
    {CHtml::dropDownList("wap_menu_list", CMenuManager::getMenu("main_menu")->getMenuLinksList())}
    <span><a href="{$no_wap_link}">Обычный режим</a></span>
</div>