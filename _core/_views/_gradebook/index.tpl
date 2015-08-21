{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Журнал успеваемости</h2>
{CHtml::helpForCurrentPage()}

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
        /**
         * Это старый код адаптированный под Bootstrap
         * Он не страшный, он оставлен таким, какой он есть, чтобы бэкэнд
         * не переписывать
         **/
        var searchResults = new Object();
        jQuery("#search").typeahead({
            source: function (query, process) {
                return jQuery.ajax({
                    url: "#",
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {
                        "query": query,
                        "action": "search"
                    },
                    beforeSend: function(){
                        /**
                         * Показываем индикатор активности
                         */
                        jQuery("#search").css({
                            "background-image": 'url({$web_root}images/ajax-loader.gif)',
                            "background-repeat": "no-repeat",
                            "background-position": "95% center"
                        });
                    },
                    success: function(data){
                        var lookup = new Array();
                        searchResults = new Object();
                        for (var i = 0; i < data.length; i++) {
                            lookup.push(data[i].label);
                            searchResults[data[i].label] = data[i];
                        }
                        process(lookup);
                        jQuery("#search").css("background-image", "none");
                    }
                });
            },
            updater: function(item){
                var selected = searchResults[item];
                if (selected.type == 1) {
                    // выбран преподаватель
                    window.location.href = "?action=index&filter=person:" + selected.object_id;
                } else if(selected.type == 2) {
                    // выбрана дисциплина
                    window.location.href = "?action=index&filter=discipline:" + selected.object_id;
                } else if(selected.type == 3) {
                    // студенческая группа
                    window.location.href = "?action=index&filter=group:" + selected.object_id;
                } else if(selected.type == 4) {
                    // студент
                    window.location.href = "?action=index&filter=student:" + selected.object_id;
                } else if(selected.type == 5) {
                    // вид контроля
                    window.location.href = "?action=index&filter=control:" + selected.object_id;
                }
            }
        });
    });
</script>
	{if $records->getCount() == 0}
		Нет журналов для отображения
	{else}
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

<form action="index.php" id="gradebookForm">
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
        <th>{CHtml::activeViewGroupSelect("id", $records->getFirstItem(), true)}</th>
    </tr>

    {foreach $records->getItems() as $record}
    <tr>
        <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить работу студента {if !is_null($record->student)}{$record->student->getName()}{/if}')) { location.href='?action=delete&id={$record->id}'; }; return false;"></a></td>
        <td><a href="?action=edit&id={$record->getId()}">{$record->getId()}</a></td>
        <td>{$record->getDate()}</td>
        <td>
            {if !is_null($record->discipline)}
                <a target="_blank" href="{$web_root}_modules/_taxonomy/index.php?action=editLegacyTerm&id={$record->discipline->getId()}&taxonomy_id=10">{$record->discipline->getValue()}</a>
            {/if}
        </td>
        <td>
            {if !is_null($record->person)}
                {if !is_null($record->person->getUser())}
                    <a href="{$web_root}_modules/_lecturers/index.php?action=view&id={$record->person->getUser()->getId()}">{$record->person->getName()}</a>
                {else}
                    {$record->person->getName()}
                {/if}
            {/if}
        </td>
        <td>
            {if (!is_null($record->student))}
                <a href="{$web_root}_modules/_students/index.php?action=edit&id={$record->student->getId()}">{$record->student->getName()}</a>
            {else}&nbsp;{/if}
        </td>
        <td>
            {if !Is_null($record->controlType)}
                {$record->controlType->getValue()}
            {else}&nbsp;{/if}
        </td>
       	<td>{$record->study_act_comment}</td>
        <td>
            {if !is_null($record->mark)}
                {$record->mark->getValue()}
            {else}&nbsp;{/if}
        </td>
        <td>{$record->comment}&nbsp;</td>
        <td>{CHtml::activeViewGroupSelect("id", $record)}</td>
    </tr>
    {/foreach}
</table>
</form>

    {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_gradebook/index.right.tpl"}
{/block}
