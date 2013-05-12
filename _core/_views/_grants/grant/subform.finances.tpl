<p>
    {CHtml::activeLabel("grant[finances_source]", $form)}
    {CHtml::activeTextField("grant[finances_source]", $form)}
    {CHtml::error("grant[finances_source]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[finances_planned]", $form)}
    {CHtml::activeTextField("grant[finances_planned]", $form)}
    {CHtml::error("grant[finances_planned]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[finances_accepted]", $form)}
    {CHtml::activeTextField("grant[finances_accepted]", $form)}
    {CHtml::error("grant[finances_accepted]", $form)}
</p>

<h3>Поступления и расходы</h3>

<table border="1" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <th>#</th>
        <th width="11">&nbsp;</th>
        <th colspan="2">Название</th>
        <th>Период времени</th>
        <th>&nbsp;</th>
    </tr>
    {foreach $form->grant->periods->getItems() as $period}
        <tr>
            <td>{counter}</td>
            <td><a href="#" onclick="if (confirm('Действительно удалить период {$period->title}')) { location.href='periods.php?action=delete&id={$period->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td colspan="2"><a href="periods.php?action=edit&id={$period->getId()}">{$period->title}</a></td>
            <td>{$period->getPeriod()}</td>
            <td width="16">
                <a href="money.php?action=add&period_id={$period->getId()}">
                    <img src="{$web_root}images/tango/16x16/actions/document-new.png">
                </a>
            </td>
        </tr>
        {foreach $period->money->getItems() as $money}
            <tr>
                <td></td>
                <td><a href="#" onclick="if (confirm('Действительно удалить операцию?')) { location.href='money.php?action=delete&id={$money->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                <td width="16">
                    {if $money->type_id == "1"}
                        <img src="{$web_root}images/tango/16x16/actions/edit-redo.png">
                    {else}
                        <img src="{$web_root}images/tango/16x16/actions/edit-undo.png">
                    {/if}
                </td>
                <td>
                    <a href="money.php?action=edit&id={$money->getId()}">{$money->value}</a>
                    {if $money->type_id == "2"}
                        {if !is_null($money->category)}
                            ({$money->category->getValue()})
                        {/if}
                    {/if}
                </td>
                <td>{$money->comment}</td>
                <td>&nbsp;</td>
            </tr>
        {/foreach}
    {/foreach}
</table>