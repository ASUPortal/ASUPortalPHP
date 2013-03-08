{extends file="_core.3col.tpl"}

{block name="asu_left"}

{/block}

{block name="asu_center"}
    {function name=orgStructAsList level=0}
    <ul class="level{$level}">
        {foreach $data as $entry}
            {if ($entry->getSubordinators()->getCount() > 0)}
                <li>
                    <a href="?action=managePerson&id={$entry->getId()}">{$entry->getName()}</a>
                    {if (!is_null($entry->getRole()))}
                        ({$entry->getRole()->getValue()})
                    {/if}
                </li>
                    {call name=orgStructAsList data=$entry->getSubordinators()->getItems() level=$level+1}
                {else}
                <li>
                    <a href="?action=managePerson&id={$entry->getId()}">{$entry->getName()}</a>
                    {if (!is_null($entry->getRole()))}
                        ({$entry->getRole()->getValue()})
                    {/if}
                </li>
            {/if}
        {/foreach}
    </ul>
    {/function}


<h2>Организационная структура</h2>
    {call name=orgStructAsList data=$persons}
{/block}

{block name="asu_right"}

{/block}