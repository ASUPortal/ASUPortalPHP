{if $object->agenda->getCount() == 0}
    Решения еще не добавлены
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tbody>
        {foreach $object->agenda->getItems() as $point}
            <tr>
                <td rowspan="2"><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить пункт')) { location.href='point.php?action=delete&id={$point->getId()}'; }; return false;"></a></td>
                <td rowspan="2"><a href="point.php?action=edit&id={$point->getId()}" class="icon-pencil"></a></td>
                <td rowspan="2">{$point->section_id}</td>
                <td><strong>Слушали:</strong></td>
                <td>{$point->getMembersAsString()} {$point->text_content}</td>
            </tr>
            <tr>
                <td><strong>Постановили:</strong></td>
                <td>
                    {if !is_null($point->opinion)}
                        {$point->opinion->getValue()}
                    {/if}
                    {$point->opinion_text}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}