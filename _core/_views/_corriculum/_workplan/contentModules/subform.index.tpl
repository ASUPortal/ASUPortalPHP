    <h2>Модули</h2>

    {if ($plan->modules->getCount() == 0)}
        Нет объектов для отображения
    {else}
        {foreach $plan->modules->getItems() as $module}
            <h3>Модуль {$module->title} </h3>

            <a href="{$web_root}_modules/_corriculum/workplancontentsections.php?action=add&id={$module->getId()}" class="btn btn-success">Добавить раздел</a>
            {include file="_corriculum/_workplan/contentModules/subform.sections.tpl"}
        {/foreach}
    {/if}