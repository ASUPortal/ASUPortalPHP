{CHtml::hiddenField("action", "saveTopic")}
{CHtml::activeHiddenField("id", $editLoadTopic)}
{CHtml::activeHiddenField("load_id", $editLoadTopic)}
<tr class="hide-required-star">
    <td>
        <a href="workplancontentloads.php?action=expand&id={$object->getId()}" class="btn btn-danger"><i class="icon-remove"></i></a>
    </td>
    <td colspan="2">
        <button type="submit" class="btn btn-success">
            <i class="icon-ok"></i>
        </button>
    </td>
    <td colspan="2">
        {CHtml::activeTextBox("title", $editLoadTopic, "", "span12")}
    </td>
    <td width="150">
        {CHtml::activeTextField("value", $editLoadTopic, "", "span12")}
    </td>
</tr>