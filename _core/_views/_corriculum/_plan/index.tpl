{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальные учебные планы</h2>
    <table cellpadding="0" cellspacing="0" border="1">
        <thead>
        <tr>
            <th>#</th>
            <th>&nbsp;</th>
            <th>Направление</th>
            <th>Профиль</th>
            <th>Форма обучения</th>
            <th>Срок обучения</th>
        </tr>
        </thead>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $corriculums->getItems() as $c}
            <tr>
                <td>{counter}</td>
                <td><a href="#" onclick="if (confirm('Действительно удалить учебный план по направлению {if !is_null($c->direction)}{$c->direction->name}{/if}')) { location.href='?action=delete&id={$c->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
                <td>
                    {if $c->direction == null}
                        -
                    {else}
                        <a href="?action=view&id={$c->id}">{$c->direction->name}</a>
                    {/if}
                </td>
                <td>
                    {if $c->profile == null}
                        -
                    {else}
                        {$c->profile->getValue()}
                    {/if}
                </td>
                <td>
                    {if $c->educationForm == null}
                        -
                    {else}
                        {$c->educationForm->getValue()}
                    {/if}
                </td>
                <td>{$c->duration}</td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/index.right.tpl"}
{/block}