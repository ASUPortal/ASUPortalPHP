<form action="tickets.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {if isset($ticket)}
        {CHtml::hiddenField("id", $ticket->getId())}
    {/if}

    <p>
        {CHtml::label("Специальность", "speciality_id")}
        {if isset($ticket)}
            {CHtml::dropDownList("speciality_id", CTaxonomyManager::getSpecialitiesList(), $ticket->getSpeciality()->getId())}
        {else}
            {CHtml::dropDownList("speciality_id", CTaxonomyManager::getSpecialitiesList(), 0)}
        {/if}
    </p>

    <p>
        {CHtml::label("Учебный год", "year_id")}
        {if isset($ticket)}
            {CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList(), $ticket->getYear()->getId())}
            {else}
            {CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList())}
        {/if}
    </p>

    <p>
        {CHtml::label("Протокол", "protocol_id")}
        {if isset($ticket)}
            {CHtml::dropDownList("protocol_id", CProtocolManager::getAllDepProtocolsList(), $ticket->getProtocol()->getId())}
        {else}
            {CHtml::dropDownList("protocol_id", CProtocolManager::getAllDepProtocolsList())}
        {/if}
    </p>

    <p>
        {CHtml::label("Подписант", "signer_id")}
        {if isset($ticket)}
            {CHtml::dropDownList("signer_id", CStaffManager::getPersonsList(), $ticket->getSigner()->getId())}
            {else}
            {CHtml::dropDownList("signer_id", CStaffManager::getPersonsList())}
        {/if}
    </p>

    <p>
        {CHtml::label("Номер билета", "number")}
        {if isset($ticket)}
            {CHtml::textField("number", $ticket->getNumber())}
            {else}
            {CHtml::textField("number")}
        {/if}
    </p>

    <div style="cursor: pointer;" onclick="ticket.addQuestion(); return false;" id="ticket_questions_adder">
        <img src="{$web_root}images/{$icon_theme}/22x22/actions/list-add.png">
        Добавить вопрос
    </div>

    <div id="ticket_questions">

    </div>

    <p>
    {CHtml::submit("Сохранить")}
    </p>
</form>