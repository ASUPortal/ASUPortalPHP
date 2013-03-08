{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Визуальные настройки портала</h2>

    {CHtml::helpForCurrentPage()}

    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th width="5"></th>
            <th width="5">#</th>
            <th>Название</th>
            <th>Псевдоним</th>
            <th>Значение</th>
        </tr>
        {foreach $settings->getItems() as $setting}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить настройку {$setting->title}')) { location.href='?action=delete&id={$setting->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$setting->id}">{$setting->title}</a></td>
            <td>{$setting->alias}</td>
            <td>{$setting->value|htmlspecialchars}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_configuration/index.right.tpl"}
{/block}