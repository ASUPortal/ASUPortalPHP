{if ($field->validators->getCount() == 0)}
    Валидация не добавлена
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("validator_id", $field->validators->getFirstItem())}</th>
        </tr>
        {foreach $field->validators->getItems() as $t}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить валидатор {if !is_null($t->validator)}{$t->validator->title}{/if}')) { location.href='fieldvalidators.php?action=delete&id={$t->id}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td>
                    <a href="fieldvalidators.php?action=edit&id={$t->getId()}">
                        {if !is_null($t->validator)}
                            {$t->validator->title}
                        {/if}
                    </a>
                </td>
            </tr>
        {/foreach}
    </table>
{/if}