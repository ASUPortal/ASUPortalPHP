<tr>
    <td colspan="6"><b>Темы</b></td>
</tr>
{foreach $object->topics as $topic}
    {if isset($editLoadTopic) && $topic->getId() == $editLoadTopic->getId()}
        {include file="_corriculum/_workplan/contentLoads/formTopic.tpl"}
    {else}
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><a href="workplancontentloads.php?action=editTopic&id={$topic->getId()}" class="icon-pencil"></a></td>
            <td colspan="2">{$topic->title}</td>
            <td>{$topic->value}</td>
        </tr>
    {/if}
{/foreach}
{if isset($editLoadTopic) && $editLoadTopic->getId() == null}
    {include file="_corriculum/_workplan/contentLoads/formTopic.tpl"}
{elseif !isset($editLoadTopic)}
    <tr>
        <td colspan="6">
            <a href="workplancontentloads.php?action=addTopic&id={$object->getId()}" class="btn btn-small btn-success">Добавить тему</a>
        </td>
    </tr>
{/if}



<tr>
    <td colspan="6"><b>Образовательные технологии</b></td>
</tr>
{foreach $object->technologies as $technology}
    <tr>
        <td>{$technology->getId()}</td>
    </tr>
{/foreach}