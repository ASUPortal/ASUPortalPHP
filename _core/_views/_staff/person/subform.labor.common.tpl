<table>
    <tr>
        <td>{CHtml::activeLabel("person[stag_ugatu]", $form)}</td>
        <td width="16px"></td>
        <td>{CStaffManager::getLengthWork($form->person->stag_ugatu)}</td>
    </tr>
    <tr>
        <td align="right">Начиная с</td>
        <td></td>
        <td align="left">{CHtml::activeDateField("person[stag_ugatu]", $form, "", "", "",'style="width: 300px;"')}</td>
    </tr>
    
    <tr>
        <td>{CHtml::activeLabel("person[stag_pps]", $form)}</td>
        <td></td>
        <td>{CStaffManager::getLengthWork($form->person->stag_pps)}</td>
    </tr>
    <tr>
        <td align="right">Начиная с</td>
        <td></td>
        <td align="left">{CHtml::activeDateField("person[stag_pps]", $form, "", "", "",'style="width: 300px;"')}</td>
    </tr>
    
    <tr>
        <td>{CHtml::activeLabel("person[stag_itogo]", $form)}</td>
        <td></td>
        <td>{CStaffManager::getLengthWork($form->person->stag_itogo)}</td>
    </tr>
    <tr>
        <td align="right">Начиная с</td>
        <td></td>
        <td align="left">{CHtml::activeDateField("person[stag_itogo]", $form, "", "", "",'style="width: 300px;"')}</td>
    </tr>
</table>
<br>
<div class="control-group">
    {CHtml::activeLabel("person[din_nauch_kar]", $form)}
    <div class="controls">
        {CHtml::activeTextBox("person[din_nauch_kar]", $form)}
        {CHtml::error("person[din_nauch_kar]", $form)}
    </div></div>

<div class="control-group">
    {CHtml::activeLabel("person[ekspert_spec]", $form)}
    <div class="controls">
        {CHtml::activeTextField("person[ekspert_spec]", $form)}
        {CHtml::error("person[ekspert_spec]", $form)}
    </div></div>

<div class="control-group">
    {CHtml::activeLabel("person[ekspert_kluch_slova]", $form)}
    <div class="controls">
        {CHtml::activeTextBox("person[ekspert_kluch_slova]", $form)}
        {CHtml::error("person[ekspert_kluch_slova]", $form)}
    </div></div>

<div class="control-group">
    {CHtml::activeLabel("person[nauch_eksper]", $form)}
    <div class="controls">
        {CHtml::activeTextBox("person[nauch_eksper]", $form)}
        {CHtml::error("person[nauch_eksper]", $form)}
    </div></div>

<div class="control-group">
    {CHtml::activeLabel("person[prepod_rabota]", $form)}
    <div class="controls">
        {CHtml::activeTextBox("person[prepod_rabota]", $form)}
        {CHtml::error("person[prepod_rabota]", $form)}
    </div></div>

<div class="control-group">
    {CHtml::activeLabel("person[nagradi]", $form)}
    <div class="controls">
        {CHtml::activeTextBox("person[nagradi]", $form)}
        {CHtml::error("person[nagradi]", $form)}
    </div></div>

<div class="control-group">
    {CHtml::activeLabel("person[primech]", $form)}
    <div class="controls">
        {CHtml::activeTextBox("person[primech]", $form)}
        {CHtml::error("person[primech]", $form)}
    </div></div>