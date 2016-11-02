{extends file="_core.component.tpl"}

{block name="asu_center"}
<h3>Список настроек коллекции поиска Solr</h3>

    {CHtml::helpForCurrentPage()}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th width="5"></th>
            <th width="5">#</th>
            <th>Название</th>
            <th>Псевдоним</th>
            <th>Значение</th>
        </tr>
        {foreach $settings->getItems() as $setting}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить настройку {$setting->title}')) { location.href='settingsList.php?action=delete&id={$setting->id}&core_id={$setting->solr_core}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td><a href="settingsList.php?action=edit&id={$setting->id}">{$setting->title}</a></td>
            <td>{$setting->alias}</td>
            <td>{$setting->value}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
	{include file="_search/_settingsList/common.right.tpl"}
{/block}