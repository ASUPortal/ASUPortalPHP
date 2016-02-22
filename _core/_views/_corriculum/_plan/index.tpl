{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальные учебные планы</h2>
    {CHtml::helpForCurrentPage()}
    
	{if $corriculums->getCount() == 0}
		Нет планов для отображения
	{else}
	<form action="index.php" method="post" id="MainView">
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <thead>
	        <tr>
	        	<th>{CHtml::activeViewGroupSelect("id", $corriculums->getFirstItem(), true)}</th>
	            <th>#</th>
	            <th>&nbsp;</th>
	            <th>{CHtml::tableOrder("title", $corriculums->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("direction.name", $corriculums->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("term.name", $corriculums->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("educ_form.name", $corriculums->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("duration", $corriculums->getFirstItem())}</th>
	        </tr>
	        </thead>
	        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $corriculums->getItems() as $c}
	            <tr>
	            	<td>{CHtml::activeViewGroupSelect("id", $c, false, true)}</td>
	                <td>{counter}</td>
	                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить учебный план по направлению {if !is_null($c->direction)}{$c->direction->name}{/if}')) { location.href='?action=delete&id={$c->id}'; }; return false;"></a></td>
	                <td>
	                    <a href="?action=view&id={$c->id}">
	                        {if ($c->title == "")}
	                            Название не указано
	                        {else}
	                            {$c->title}
	                        {/if}
	                    </a>
	                </td>
	                <td>
	                    {if $c->direction == null}
	                        -
	                    {else}
	                        {$c->direction->getValue()}
	                        {if $c->direction->comment !== ""}
	                            ({$c->direction->comment})
	                        {/if}
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
	</form>
	{CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/index.right.tpl"}
{/block}