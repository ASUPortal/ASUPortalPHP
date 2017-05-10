    <h3>Семестры</h3>
	<a href="{$web_root}_modules/_corriculum/workplancontentcategories.php?action=add&id={$plan->getId()}" class="btn btn-success" target="_blank">Добавить категорию</a>
	
    {if ($plan->categories->getCount() == 0)}
        Нет объектов для отображения
    {else}
        {foreach $plan->categories->getItems() as $category}
            <h4><a href="{$web_root}_modules/_corriculum/workplancontentcategories.php?action=edit&id={$category->getId()}" target="_blank">{$category->title}</a></h4>
            
            <a href="{$web_root}_modules/_corriculum/workplancontentsections.php?action=add&id={$category->getId()}" class="btn btn-success">Добавить раздел</a>
            {include file="_corriculum/_workplan/contentCategories/subform.sections.tpl"}
        {/foreach}
    {/if}