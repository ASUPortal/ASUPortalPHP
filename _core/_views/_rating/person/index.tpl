{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Показатели преподавателей</h2>

{CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList(), $year->getId(), "", "", "onchange='location.href=\"persons.php?action=index&year=\" + this.value'")}

<table cellpadding="0" cellspacing="0" border="1">
    <thead>
    <tr>
        <th><img src="{$web_root}images/todelete.png"></th>
        <th>#</th>
        <th>Преподаватель</th>
        <th>Показатель</th>
        <th>Значение</th>
    </tr>
    </thead>
    {foreach $persons->getItems() as $person}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить показатель {$person->getName()}')) { location.href='?action=delete&id={$person->id}&year={$year->getId()}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{$person->id}</td>
            <td><a href="persons.php?action=view&id={$person->id}&year={$year->getId()}">{$person->getName()}</a></td>
            <td>
                <ul>
                {foreach $person->getRatingIndexesByYear($year)->getItems() as $index}
                    <li>
                        {$index->title}
                        <ol>
                            {foreach $index->getIndexValues()->getItems() as $value}
                                <li>{$value->getTitle()}</li>
                            {/foreach}
                        </ol>
                    </li>
                {/foreach}
                </ul>
            </td>
            <td align="center">{$person->getRatingIndexValue($year)}</td>
        </tr>
    {/foreach}
</table>
{/block}

{block name="asu_right"}
{include file="_rating/person/index.right.tpl"}
{/block}