{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>{$cycle->number} {$cycle->title}</h2>

    <p><strong>Базовая часть</strong></p>
        <table class="table table-striped table-bordered table-hover table-condensed">
        {foreach $cycle->basicDisciplines->getItems() as $item}
        <tr>
            <td>{$item->number}</td>
            <td><a href="?action=viewDiscipline&id={$item->id}">{$item->discipline->getValue()}</a></td>
        </tr>
            {foreach $item->children->getItems() as $child}
                <tr>
                    <td>{$child->number}</td>
                    <td> - <a href="?action=viewDiscipline&id={$child->id}">{$child->discipline->getValue()}</a></td>
                </tr>
            {/foreach}
        {/foreach}
        </table>
    <p><strong>Вариативная часть</strong></p>
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/viewCycle.right.tpl"}
{/block}