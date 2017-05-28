{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Студенческие группы</h2>

    {CHtml::helpForCurrentPage()}
    
    {include file="_core.searchLocal.tpl"}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::activeViewGroupSelect("id", $groups->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("name", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("students_count", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("speciality_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("head_student_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("year_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("curator_id", $groups->getFirstItem())}</th>
            <th>{CHtml::tableOrder("corriculum_id", $groups->getFirstItem())}</th>
            <th>Комментарий</th>
        </tr>
        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $groups->getItems() as $group}
            <tr>
                <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить группу {$group->name}')) { location.href='?action=delete&id={$group->id}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td>{CHtml::activeViewGroupSelect("id", $group, false, true)}</td>
                <td><a href="?action=edit&id={$group->getId()}">{$group->name}</a></td>
                <td>{$group->getStudentsCount()}</td>
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
                		<a href="{$web_root}_modules/_corriculum/index.php?action=view&id={$group->corriculum->getId()}">
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
