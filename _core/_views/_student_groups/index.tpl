{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Студенческие группы</h2>

    {CHtml::helpForCurrentPage()}
    
    <script>
	    jQuery(document).ready(function(){
		    jQuery("#search").autocomplete({
                source: web_root + "_modules/_student_groups/?action=search",
                minLength: 2,
                select: function(event, ui) {
                    if (ui.item.type == 1) {
                        // выбрана учебная группа
                        window.location.href = "?action=index&filter=group:" + ui.item.object_id;
                    }
                }
            });
	    });
        /**
         * Очистка указанного фильтра
         * @param type
         */
        function removeFilter(type) {
            var filters = new Object();
            {if !is_null($selectedGroup)}
                filters.group = {$selectedGroup->getId()};
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
    </script>
    
	<table border="0" width="100%" class="tableBlank">
	    <tr>
	        <td valign="top">
	            <form>
		        {if !is_null($selectedGroup)}
	            <p>
	            	<label for="group">Учебная группа</label>
	            	{$selectedGroup->getName()}
	            	<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('group'); return false; "/></span>
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
            <th>{CHtml::tableOrder("name", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("students_cnt", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("speciality_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("head_student_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("year_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("curator_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("corriculum_id", $groups->getFirstItem())}</th>
            <th>Комментарий</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $groups->getItems() as $group}
            <tr>
                <td><a href="#" onclick="if (confirm('Действительно удалить группу {$group->name}')) { location.href='?action=delete&id={$group->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                <td>{counter}</td>
                <td><a href="?action=edit&id={$group->getId()}">{$group->name}</a></td>
                <td>{$group->getStudents()->getCount()}</td>
                <td>
                    {if !is_null($group->getSpeciality())}
                        {$group->getSpeciality()->getValue()}
                    {/if}
                </td>
                <td>
                    {if !is_null($group->monitor)}
                        {$group->monitor->getName()}
                    {/if}
                </td>
                <td>
                    {if !is_null($group->getYear())}
                        {$group->getYear()->getValue()}
                    {/if}
                </td>
                <td>
                    {if !is_null($group->curator)}
                        {$group->curator->getName()}
                    {/if}
                </td>
                <td>
                	{if !is_null($group->corriculum)}
                		<a href="{$web_root}_modules/_corriculum/?action=view&id={$group->corriculum->getId()}">
                			{$group->corriculum->title}
                		</a>
                	{/if}
                </td>
                <td>{$group->comment}</td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_student_groups/index.right.tpl"}
{/block}