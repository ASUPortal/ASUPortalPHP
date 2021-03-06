{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Визуальные настройки портала</h2>

    {CHtml::helpForCurrentPage()}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th width="5"></th>
            <th width="5">#</th>
            <th>{CHtml::tableOrder("title", $settings->getFirstItem())}</th>
            <th>{CHtml::tableOrder("alias", $settings->getFirstItem())}</th>
            <th>{CHtml::tableOrder("value", $settings->getFirstItem())}</th>
            <th>{CHtml::tableOrder("description", $settings->getFirstItem())}</th>
        </tr>
        {foreach $settings->getItems() as $setting}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить настройку {$setting->title}')) { location.href='?action=delete&id={$setting->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$setting->id}">{$setting->title}</a></td>
            <td>{$setting->alias}</td>
            <td>{$setting->value|htmlspecialchars}</td>
            <td>{$setting->description}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_configuration/index.right.tpl"}
{/block}