<div class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3>Дни рождения</h3>
    </div>
    <div class="modal-body">
        <ul>
            {foreach $persons->getItems() as $person}
                <li><b>{$person->getName()}</b> - {$person->getBirthday()}</li>
            {/foreach}
        </ul>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</a>
    </div>
</div>