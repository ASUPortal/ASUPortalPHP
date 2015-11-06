<tr>
    <td colspan="6"><b>Темы</b></td>
</tr>
{foreach $object->topics as $topic}
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><a href="workplancontentloads.php?action=editTopic&id={$topic->getId()}" class="icon-pencil"></a></td>
        <td colspan="2">{$topic->title}</td>
        <td>{$topic->value}</td>
    </tr>
{/foreach}
{if isset($editLoadTopic)}
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
{else}
    <tr>
        <td colspan="6">
            <a href="workplancontentloads.php?action=addTopic&id={$object->getId()}" class="btn btn-small btn-success">Добавить тему</a>
        </td>
    </tr>
{/if}