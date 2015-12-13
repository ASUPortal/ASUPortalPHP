{if $model->validators->getCount() == 0}
    Нет валидаторов для модели
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("validator_id", $model->validators->getFirstItem())}</th>
            <th>&nbsp;</th>
        </tr>
        {foreach $model->validators->getItems() as $t}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить валидатор {if !is_null($t->validator)}{$t->validator->title}{/if}')) { location.href='modelvalidators.php?action=delete&id={$t->id}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td>
                    <a href="modelvalidators.php?action=edit&id={$t->getId()}">
                        {if !is_null($t->validator)}
                            {$t->validator->title}
                        {/if}
                    </a>
                </td>
                <td>
                    {if $t->validator->type_id == 3}
                        Опционально
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
{/if}