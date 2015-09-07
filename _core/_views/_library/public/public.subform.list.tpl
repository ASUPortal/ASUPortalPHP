{foreach $folders->getItems() as $folder}
    <div>
        <b>
            {if !is_null($folder->getDiscipline())}
                <a href="?action=index&filter=subject:{$folder->getDiscipline()->getId()}">{$folder->getDiscipline()->getValue()}</a>
            {/if}
        </b>
        <ul>
            {if is_null($folder->getPersons()->getItems())}
            {foreach $folder->getPersons()->getItems() as $person}
                <li>
                    <a href="{$web_root}_modules/_lecturers/index.php?action=view&id={$person->getUser()->getId()}">{$person->getDisplayName()}</a> -
                    <a href="?action=publicView&id={$folder->getFolderIds()->getItem($person->getUser()->getId())}">найдено материалов преподавателя ({$folder->getMaterialsCount($folder->getFolderIds()->getItem($person->getUser()->getId()))})</a>
                </li>
            {/foreach}
            {else}
	            {foreach $folder->getUsers()->getItems() as $user}
	                <li>
	                    <a href="{$web_root}_modules/_lecturers/index.php?action=view&id={$user->getId()}">{$user->getName()}</a> -
	                    <a href="?action=publicView&id={$folder->getFolderIds()->getItem($user->getId())}">найдено материалов преподавателя ({$folder->getMaterialsCount($folder->getFolderIds()->getItem($user->getId()))})</a>
	                </li>
	            {/foreach}
            {/if}
        </ul>
    </div>
{/foreach}

{CHtml::paginator($paginator, "?action=index")}