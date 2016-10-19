    <h3>Категории</h3>

    {if ($plan->categories->getCount() == 0)}
        Нет объектов для отображения
    {else}
        {foreach $plan->categories->getItems() as $category}
            <h4>Категория {$category->title} </h4>

            <a href="{$web_root}_modules/_corriculum/workplancontentsections.php?action=add&id={$category->getId()}" class="btn btn-success">Добавить раздел</a>
            <a href="{$web_root}_modules/_corriculum/workplancontentcategories.php?action=delete&id={$category->getId()}" class="btn btn-danger">Удалить категорию</a>
            {include file="_corriculum/_workplan/contentCategories/subform.sections.tpl"}
        {/foreach}
    {/if}