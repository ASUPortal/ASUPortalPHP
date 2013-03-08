<form action="index.php" method="post">
    {CHtml::hiddenField("action", "save")}
    {if isset($event)}
        {CHtml::hiddenField("id", $event->getId())}
    {else}

    {/if}

    <p>
        {CHtml::label("Название", "name")}
            {if isset($event)}
            {CHtml::textField("name", $calendar->getName())}
            {else}
            {CHtml::textField("name")}
        {/if}
    </p>

    <p>
        {CHtml::label("Описание", "description")}
            {if isset($event)}
            {CHtml::textBox("description", $calendar->getDescription())}
            {else}
            {CHtml::textBox("description")}
        {/if}
    </p>

    <p>
        {CHtml::label("Календарь", "calendar_id")}
            {if isset($event)}
            {CHtml::textField("name", $calendar->getName())}
            {else}
            {CHtml::dropDownList("calendar_id", $calendars)}
        {/if}
    </p>

    <p>
        {CHtml::label("Дата начала", "eventStart")}
            {if isset($event)}
                {CHtml::textField("eventStart", $calendar->getDescription())}
            {else}
                {CHtml::textField("eventStart")}
        {/if}
    </p>

    <p>
        {CHtml::label("Дата окончания", "eventEnd")}
            {if isset($event)}
            {CHtml::textField("eventEnd", $calendar->getDescription())}
            {else}
            {CHtml::textField("eventEnd")}
        {/if}
    </p>

        <p>
            {CHtml::label("Участники", "members_show")}
            <ul id="members_show" class="ul-input" name="members[]"></ul>
        </p>
    
    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>