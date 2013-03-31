{foreach $folders->getItems() as $folder}
    <div>
        <b>
            {if !is_null($folder->getDiscipline())}
                <a href="?action=index&filter=subject:{$folder->getDiscipline()->getId()}">{$folder->getDiscipline()->getValue()}</a>
            {/if}
        </b>
        <ul>
            {foreach $folder->getPersons()->getItems() as $person}
                <li>
                    <a href="?action=index&filter=author:{$person->getUser()->getId()}">{$person->getDisplayName()}</a> -
                    <a href="?action=view&id={$folder->getFolderIds()->getItem($person->getUser()->getId())}">найдено материалов преподавателя ({$folder->getMaterialsCount()->getItem($person->getUser()->getId())})</a>
                </li>
            {/foreach}
        </ul>
    </div>
{/foreach}

{CHtml::paginator($paginator, "?action=index")}