{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Гранты</h2>

    {if ($grants->getCount() == 0)}
        Нет грантов для отображения
    {else}
        <table border="1" width="100%" cellpadding="2" cellspacing="0">
            <tr>
                <th>#</th>
                <th>&nbsp;</th>
                <th>{CHtml::tableOrder("title", $grants->getFirstItem())}</th>
                <th>{CHtml::tableOrder("comment", $grants->getFirstItem())}</th>
            </tr>
            {foreach $grants->getItems() as $grant}
                <tr>
                    <td>{counter}</td>
                    <td><a href="#" onclick="if (confirm('Действительно удалить грант {$grant->title}')) { location.href='?action=delete&id={$grant->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                    <td><a href="index.php?action=edit&id={$grant->getId()}">
                            {if $grant->author_id == CSession::getCurrentPerson()->getId()}
                                <b>{$grant->title}</b>
                            {else}
                                {$grant->title}
                            {/if}
                        </a></td>
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