{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Валидаторы</h2>

    {CHtml::helpForCurrentPage()}

    {if $validators->getCount() == 0}
        Нет зарегистрированных валидаторов
    {else}

        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th></th>
                <th>#</th>
                <th>{CHtml::tableOrder("title", $validators->getFirstItem())}</th>
                <th>{CHtml::tableOrder("class_name", $validators->getFirstItem())}</th>
                <th>{CHtml::tableOrder("comment", $validators->getFirstItem())}</th>
            </tr>
            {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $validators->getItems() as $validator}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить валидатор {$validator->title}')) { location.href='?action=delete&id={$validator->id}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td>
                        <a href="?action=edit&id={$validator->getId()}">
                            {$validator->title}
                        </a>
                    </td>
                    <td>{$validator->class_name}</td>
                    <td>{$validator->comment}</td>
                </tr>
            {/foreach}
        </table>

        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_core/validator/index.right.tpl"}
{/block}