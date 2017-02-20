{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if $objects->getCount() == 0}
        Нет объектов для отображения
    {else}
    	{foreach $objects->getItems() as $term_id=>$termData}
    	<h4>
	    	{if !is_null(CBaseManager::getWorkPlanTerm($term_id)->corriculum_discipline_section)}
	        	{CBaseManager::getWorkPlanTerm($term_id)->corriculum_discipline_section->title} семестр
	        {else}
	        	<td><font color="#FF0000">Обновите значение семестра из дисциплины!</font></td>
	        {/if}
    	</h4>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>№ л/р</th>
                <th>№ раздела</th>
                <th>Наименование лабораторных работ</th>
                <th>Число часов</th>
            </tr>
            </thead>
            <tbody>
                {foreach $termData as $lab}
                    <tr>
                        <td>{$lab->ordering}</td>
                        <td>{$lab->load->section->sectionIndex}</td>
                        <td><a href="{$web_root}_modules/_corriculum/workplancontent.php?action=editLabWorks&id={$lab->getId()}">{$lab->title}</a></td>
                        <td>{$lab->value}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
        {/foreach}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/content/common.right.tpl"}
{/block}