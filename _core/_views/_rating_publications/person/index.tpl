{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Показатели преподавателей</h2>
{CHtml::helpForCurrentPage()}

{CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList(), $year->getId(), "", "", "onchange='location.href=\"persons.php?action=index&year=\" + this.value'")}

    <table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th>#</th>
        <th>Преподаватель</th>
        <th>Тип публикации - вес</th>
        <th>Суммарный вес</th>
    </tr>
    </thead>
    {foreach $persons->getItems() as $person}
        <tr>
            <td>{counter}</td>
            <td>{$person->getName()}</td>
            <td>
                <ul>
                {foreach $person->getPublications($year)->getItems() as $index}
                    <li>
                    	{if !is_null($index->type)}
                            <a href="{$web_root}_modules/_staff/publications.php?action=edit&id={$index->id}">{$index->type->getValue()}</a> - {$index->type->weight}
                        {else}
                        	<a href="{$web_root}_modules/_staff/publications.php?action=edit&id={$index->id}">Тип публикации не указан!</a>
                        {/if}
                    </li>
                {/foreach}
                </ul>
            </td>
            <td>{$person->getRatingPublicationsWeight($year)}</td>
        </tr>
    {/foreach}
</table>
{/block}

{block name="asu_right"}
	{include file="_rating_publications/person/index.right.tpl"}
{/block}