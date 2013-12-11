{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Гранты</h2>

    {if ($grants->getCount() == 0)}
        Нет грантов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>&nbsp;</th>
                <th>{CHtml::tableOrder("title", $grants->getFirstItem())}</th>
                <th>{CHtml::tableOrder("date_start", $grants->getFirstItem())}</th>
                <th>{CHtml::tableOrder("date_end", $grants->getFirstItem())}</th>
                <th>{CHtml::tableOrder("comment", $grants->getFirstItem())}</th>
            </tr>
            {foreach $grants->getItems() as $grant}
                <tr>
                    <td>{counter}</td>
                    <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить грант {$grant->title}')) { location.href='?action=delete&id={$grant->id}'; }; return false;"></a></td>
                    <td><a href="index.php?action=edit&id={$grant->getId()}">
                            {if $grant->author_id == CSession::getCurrentPerson()->getId()}
                                <b>{$grant->title}</b>
                            {else}
                                {$grant->title}
                            {/if}
                        </a></td>
                    <td>{$grant->date_start}</td>
                    <td>{$grant->date_end}</td>
                    <td>{$grant->comment}</td>
                </tr>
            {/foreach}
        </table>
    {/if}

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_grants/grant/index.right.tpl"}
{/block}
