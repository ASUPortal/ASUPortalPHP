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

<h3>Периоды</h3>

<table border="1" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Название</th>
        <th>Период времени</th>
    </tr>
</table>