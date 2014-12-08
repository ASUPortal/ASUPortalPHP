{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Журнал успеваемости</h2>

<script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedPerson)}
            filters.person_id = {$selectedPerson};
        {/if}
        {if !is_null($selectedGroup)}
            filters.group = {$selectedGroup};
        {/if}
        {if !is_null($selectedDiscipline)}
            filters.discipline = {$selectedDiscipline};
        {/if}
        {if !is_null($selectedControl)}
            filters.control = {$selectedControl->getId()};
        {/if}
        {if !is_null($selectedStudent)}
            filters.student = {$selectedStudent->getId()};
        {/if}
        var action = "?action=index&filter=";
        var actions = new Array();
        jQuery.each(filters, function(key, value){
            if (key !== type) {
                actions[actions.length] = key + ":" + value;
            }
        });
        action = action + actions.join("_");
        window.location.href = action;
    }
    jQuery(document).ready(function(){
        jQuery("#person").change(function(){
            window.location.href = "?action=index&filter=person:" + jQuery(this).val()
            {if !is_null($selectedGroup)}
                + "_group:{$selectedGroup}"
            {/if}
            {if !is_null($selectedDiscipline)}
                + "_discipline:{$selectedDiscipline}"
            {/if}
            {if !is_null($selectedControl)}
                + "_control:{$selectedControl->getId()}"
            {/if}
            {if !is_null($selectedStudent)}
                + "_student:{$selectedStudent->getId()}"
            {/if};
        });
        jQuery("#group").change(function(){
            window.location.href = "?action=index&filter=group:" + jQuery(this).val()
            {if !is_null($selectedPerson)}
                + "_person:{$selectedPerson}"
            {/if}
            {if !is_null($selectedDiscipline)}
                + "_discipline:{$selectedDiscipline}"
            {/if}
            {if !is_null($selectedControl)}
                + "_control:{$selectedControl->getId()}"
            {/if}
            {if !is_null($selectedStudent)}
                + "_student:{$selectedStudent->getId()}"
            {/if};
        });
        jQuery("#discipline").change(function(){
            window.location.href = "?action=index&filter=discipline:" + jQuery(this).val()
            {if !is_null($selectedPerson)}
                + "_person:{$selectedPerson}"
            {/if}
            {if !is_null($selectedGroup)}
                + "_group:{$selectedGroup}"
            {/if}
            {if !is_null($selectedControl)}
                + "_control:{$selectedControl->getId()}"
            {/if}
            {if !is_null($selectedStudent)}
                + "_student:{$selectedStudent->getId()}"
            {/if};
        });
        jQuery("#search").autocomplete({
            source: web_root + "_modules/_gradebook/?action=search",
            minLength: 2,
            select: function(event, ui) {
                if (ui.item.type == 1) {
                    // выбран преподаватель
                    window.location.href = "?action=index&filter=person:" + ui.item.object_id;
                } else if(ui.item.type == 2) {
                    // выбрана дисциплина
                    window.location.href = "?action=index&filter=discipline:" + ui.item.object_id;
                } else if(ui.item.type == 3) {
                    // студенческая группа
                    window.location.href = "?action=index&filter=group:" + ui.item.object_id;
                } else if(ui.item.type == 4) {
                    // студент
                    window.location.href = "?action=index&filter=student:" + ui.item.object_id;
                } else if(ui.item.type == 5) {
                    // вид контроля
                    window.location.href = "?action=index&filter=control:" + ui.item.object_id;
                }
            }
        });
    });
</script>

<table border="0" width="100%" class="tableBlank">
    <tr>
        <td valign="top">
            <form>
            <p>
                <label for="person">Преподаватель</label>
                {CHtml::dropDownList("person", $persons, $selectedPerson, "person")}
                {if !is_null($selectedPerson)}
                    <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('person'); return false; "/></span>
                {/if}
            </p>
            <p>
                <label for="group">Группа</label>
                {CHtml::dropDownList("group", $groups, $selectedGroup, "group")}
                {if !is_null($selectedGroup)}
                    <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('group'); return false; "/></span>
                {/if}
            </p>
            <p>
                <label for="discipline">Дисциплина</label>
                {CHtml::dropDownList("discipline", $disciplines, $selectedDiscipline, "discipline")}
                {if !is_null($selectedDiscipline)}
                    <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('discipline'); return false; "/></span>
                {/if}
            </p>
            {if !is_null($selectedStudent)}
            <p>
                <label for="student">Студент</label>
                {$selectedStudent->getName()}
                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('student'); return false; "/></span>
            </p>
            {/if}
            {if !is_null($selectedControl)}
            <p>
                <label for="control">Вид контроля</label>
                {$selectedControl->getValue()}
                <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('control'); return false; "/></span>
            </p>
            {/if}
            </form>
        </td>
        <td valign="top" width="200px">
            <p>
                <input type="text" id="search" style="width: 100%; " placeholder="Поиск">
            </p>
        </td>
    </tr>
</table>

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>&nbsp;</th>
        <th>№</th>
        <th>{CHtml::tableOrder("date_act", $records->getFirstItem())}</th>
        <th>{CHtml::tableOrder("subject_id", $records->getFirstItem())}</th>
        <th>{CHtml::tableOrder("kadri_id", $records->getFirstItem())}</th>
        <th>{CHtml::tableOrder("student_id", $records->getFirstItem())}</th>
        <th>Вид контроля</th>
        <th>№</th>
        <th>Оценка</th>
        <th>Комментарий</th>
    </tr>

    {foreach $records->getItems() as $record}
    <tr>
        <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить работу стедунта {if !is_null($record->student)}{$record->student->getName()}{/if}')) { location.href='?action=delete&id={$record->id}'; }; return false;"></a></td>
        <td><a href="?action=edit&id={$record->getId()}">{$record->getId()}</a></td>
        <td>{$record->getDate()}</td>
        <td>
            {if !is_null($record->discipline)}
                <a target="_blank" href="{$web_root}spravochnik_edit.php?item_id={$record->discipline->getId()}&type=edit&sprav_id=10">{$record->discipline->getValue()}</a>
            {/if}
        </td>
        <td>
            {if !is_null($record->person)}
                {if !is_null($record->person->getUser())}
                    <a href="{$web_root}p_lecturers.php?onget=1&idlect={$record->person->getUser()->getId()}">{$record->person->getName()}</a>
                {else}
                    {$record->person->getName()}
                {/if}
            {/if}
        </td>
        <td>{if (!is_null($record->student))}
            <a href="{$web_root}_modules/_students/?action=edit&id={$record->student->getId()}">{$record->student->getName()}</a>
        {else}&nbsp;{/if}</td>
        <td>
        {if !Is_null($record->controlType)}
        	{$record->controlType->getValue()}
       	{else}&nbsp;{/if}</td>
       	<td>{$record->study_act_comment}</td>
        <td>
	{if !is_null($record->mark)}
		{$record->mark->getValue()}
	{else}&nbsp;{/if}
	</td>
        <td>{$record->comment}&nbsp;</td>
    </tr>
    {/foreach}
</table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_gradebook/index.right.tpl"}
{/block}
