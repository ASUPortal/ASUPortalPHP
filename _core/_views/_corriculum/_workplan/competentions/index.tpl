{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
    	<form id="competentionsForm">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::activeViewGroupSelect("id", $objects->getFirstItem(), true)}</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("competention_id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("level_id", $objects->getFirstItem())}</th>
                {if (CRequest::getInt("type")) != 0}
                    <th>{CHtml::tableOrder("discipline_id", $objects->getFirstItem())}</th>
                {else}
                    <th>{CHtml::tableOrder("knowledges", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("skills", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("experiences", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("canUse", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("type_task", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("procedure_eval", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("criteria_eval", $objects->getFirstItem())}</th>
                {/if}
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td>
                        {if $object->allow_delete == "1"}
                            <a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить компетенцию')) { location.href='workplancompetentions.php?action=delete&id={$object->getId()}'; }; return false;"></a>
                        {/if}
                    </td>
                    <td>{CHtml::activeViewGroupSelect("id", $object)}</td>
                    <td>{counter}</td>
                    <td><a href="workplancompetentions.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>
                    	{if (!is_null($object->competention))}
                    		{$object->competention}
                    	{/if}
                    </td>
                    <td>
                    	{if (!is_null($object->level))}
                    		{$object->level->getValue()}
                    	{/if}
                    </td>
                {if ($object->type) != 0}
                    <td>
                    	{if (!is_null($object->discipline->discipline))}
                    		{$object->discipline->discipline->getValue()}
                    	{/if}
                    </td>
                {else}
                	<td>
                        {foreach $object->knowledges->getItems() as $o}
                            <p>{$o}</p>
                        {/foreach}
                    </td>
                    <td>
                        {foreach $object->skills->getItems() as $o}
                            <p>{$o}</p>
                        {/foreach}
                    </td>
                    <td>
                        {foreach $object->experiences->getItems() as $o}
                            <p>{$o}</p>
                        {/foreach}
                    </td>
                    <td>
                        {foreach $object->canUse->getItems() as $o}
                            <p>{$o}</p>
                        {/foreach}
                    </td>
                    <td>{$object->type_task}</td>
                    <td>{$object->procedure_eval}</td>
                    <td>{$object->criteria_eval}</td>
                {/if}                   
                </tr>
            {/foreach}
            </tbody>
        </table>
        </form>

        {CHtml::paginator($paginator, "workplancompetentions.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/competentions/common.right.tpl"}
{/block}