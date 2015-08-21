{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Хоздоговора и гранты</h2>
    {if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td>{counter}</td>
                    <td>
                        <a href="grants.php?action=view&id={$object->getId()}">{$object->title}</a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "grants.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="__public/_grants//index.right.tpl"}
{/block}