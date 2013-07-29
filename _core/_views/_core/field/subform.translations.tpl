{if $field->translations->getCount() == 0}
    Переводы не добавлены
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("value", $field->translations->getFirstItem())}</th>
            <th>{CHtml::tableOrder("language_id", $field->translations->getFirstItem())}</th>
        </tr>
        {foreach $field->translations->getItems() as $t}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить перевод {$t->value}')) { location.href='translations.php?action=delete&id={$t->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td>
                <a href="translations.php?action=edit&id={$t->getId()}">
                    {$t->value}
                </a>
            </td>
            <td>
                {if !is_null($t->language)}
                    {$t->language->getValue()}
                {/if}
            </td>
        </tr>
        {/foreach}
    </table>
{/if}