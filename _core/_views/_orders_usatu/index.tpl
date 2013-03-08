{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Приказы УГАТУ</h2>

    {CHtml::helpForCurrentPage()}

<script>
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedGroup)}
            filters.group = {$selectedGroup};
        {/if}
        {if !is_null($selectedStudent)}
            filters.student = {$selectedStudent->getId()};
        {/if}
        {if !is_null($selectedDiplom)}
            filters.diplom = {$selectedDiplom->getId()};
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
        jQuery("#group").change(function(){
                window.location.href = "?action=index&filter=group:" + jQuery(this).val()
            {if !is_null($selectedStudent)}
                    + "_student:{$selectedStudent->getId()}";
            {/if}
            ;
        });
        jQuery("#search").autocomplete({
            source: web_root + "_modules/_students/?action=search",
            minLength: 2,
            select: function(event, ui) {
                if (ui.item.type == 1) {
                    // выбрана группа
                    window.location.href = "?action=index&filter=group:" + ui.item.object_id;
                } else if(ui.item.type == 2) {
                    // выбран студент
                    window.location.href = "?action=index&filter=student:" + ui.item.object_id;
                } else if(ui.item.type == 3) {
                    // выбрана тема диплома
                    window.location.href = "?action=index&filter=diplom:" + ui.item.object_id;
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
                    <label for="group">Группа</label>
                    {CHtml::dropDownList("group", $groups, $selectedGroup, "group")}
                    {if !is_null($selectedGroup)}
                        <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('group'); return false; "/></span>
                    {/if}
                </p>
                {if !is_null($selectedStudent)}
                    <p>
                        <label for="student">Студент</label>
                        {$selectedStudent->getName()}
                        <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('student'); return false; "/></span>
                    </p>
                {/if}
                {if !is_null($selectedDiplom)}
                    <p>
                        <label for="student">Диплом</label>
                        {$selectedDiplom->dipl_name}
                        <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('diplom'); return false; "/></span>
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

<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>{CHtml::tableOrder("fio", $students->getFirstItem())}</th>
        <th>{CHtml::tableOrder("stud_num", $students->getFirstItem())}</th>
        <th>{CHtml::tableOrder("group_id", $students->getFirstItem())}</th>
        <th>{CHtml::tableOrder("bud_contract", $students->getFirstItem())}</th>
        <th>{CHtml::tableOrder("telephone", $students->getFirstItem())}</th>
        <th>{CHtml::tableOrder("diploms", $students->getFirstItem())}</th>
        <th>Комментарий</th>
    </tr>
    {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $students->getItems() as $student}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить стедунта {$student->fio}')) { location.href='?action=delete&id={$student->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$student->getId()}">{$student->getName()}</a></td>
            <td>{$student->stud_num}</td>
            <td>
                {if !is_null($student->getGroup())}
                    {$student->getGroup()->getName()}
                {/if}
            </td>
            <td>{$student->getMoneyForm()}</td>
            <td>{$student->telephone}</td>
            <td>
                {foreach $student->diploms->getItems() as $diplom}
                    <p><a href="{$web_root}diploms_view.php?item_id={$diplom->getId()}&type=edit">{$diplom->dipl_name}</a></p>
                {/foreach}
            </td>
            <td>{$student->comment}</td>
        </tr>
    {/foreach}
</table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_orders_usatu/index.right.tpl"}
{/block}