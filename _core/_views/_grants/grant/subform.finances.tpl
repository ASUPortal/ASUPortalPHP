<p>
    {CHtml::activeLabel("grant[finances_total]", $form)}
    {CHtml::activeTextField("grant[finances_total]", $form)}
    {CHtml::error("grant[finances_total]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[finances_by_period]", $form)}
    {CHtml::activeTextField("grant[finances_by_period]", $form)}
    {CHtml::error("grant[finances_by_period]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[finances_period_type_id]", $form)}
    {CHtml::activeDropDownList("grant[finances_period_type_id]", $form, CTaxonomyManager::getTaxonomy("finances_period")->getTermsList())}
    {CHtml::error("grant[finances_period_type_id]", $form)}
</p>

<p>
    {CHtml::activeLabel("grant[finances_accepted]", $form)}
    {CHtml::activeTextField("grant[finances_accepted]", $form)}
    {CHtml::error("grant[finances_accepted]", $form)}
</p>

<h3>Расходы по статьям</h3>

<table border="1" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Статья</th>
        <th>Сумма</th>
    </tr>
    {foreach $form->grant->outgoes->getItems() as $outgo}
        <tr>
            <td>{counter}</td>
            <td><a href="#" onclick="if (confirm('Действительно удалить расход?')) { location.href='outgoes.php?action=delete&id={$outgo->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>
                {if !is_null($outgo->category)}
                    <a href="outgoes.php?action=edit&id={$outgo->getid()}">{$outgo->category->getValue()}</a>
                {/if}
            </td>
            <td>{$outgo->value}</td>
        </tr>
    {/foreach}
</table>