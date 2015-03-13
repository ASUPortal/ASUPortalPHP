{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Модели данных портала</h2>

    {CHtml::helpForCurrentPage()}

    {if $models->getCount() == 0}
        Нет зарегистрированных моделей
    {else}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("title", $models->getFirstItem())}</th>
            <th>{CHtml::tableOrder("class_name", $models->getFirstItem())}</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $models->getItems() as $model}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить модель {$model->title}')) { location.href='?action=delete&id={$model->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td>
                <a href="?action=edit&id={$model->getId()}">
                    {$model->title}
                </a>
            </td>
            <td>{$model->class_name}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_core/model/common.right.tpl"}
{/block}