{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Результаты импорта</h2>

    {CHtml::helpForCurrentPage()}

    {foreach $results->getItems() as $type=>$students}
        <p><strong>{$type}</strong></p>

        <ol>
            {foreach $students->getSortedByKey(true)->getItems() as $student=>$fields}
                <li>
                    {$student}
                    {if (is_object($fields))}
                        <ul>
                            {foreach $fields->getItems() as $key=>$value}
                                <li>{$key} - {$value}</li>
                            {/foreach}
                        </ul>
                    {/if}
                </li>
            {/foreach}
        </ol>
    {/foreach}
{/block}

{block name="asu_right"}
{include file="_students/common.right.tpl"}
{/block}