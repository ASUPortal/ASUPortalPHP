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

        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_core/model/index.right.tpl"}
{/block}