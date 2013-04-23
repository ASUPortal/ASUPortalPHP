<ul>
    {foreach $persons->getItems() as $person}
        <li><b>{$person->getName()}</b> - {$person->getBirthday()}</li>
    {/foreach}
</ul>