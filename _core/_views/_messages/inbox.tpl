{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Студенты</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core.searchLocal.tpl"}

    <form action="index.php" method="post" id="MainView">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>{CHtml::activeViewGroupSelect("id", $students->getFirstItem(), true)}</th>
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
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить студента {$student->fio}')) { location.href='?action=delete&id={$student->id}'; }; return false;"></a></td>
            <td>{CHtml::activeViewGroupSelect("id", $student)}</td>
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
                    <p><a href="{$web_root}_modules/_diploms/?action=edit&id={$diplom->getId()}">{$diplom->dipl_name}</a></p>
                {/foreach}
            </td>
            <td>{$student->comment}</td>
        </tr>
        {/foreach}
    </table>
    </form>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_students/common.right.tpl"}
{/block}
