{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Модули</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        {foreach $objects->getItems() as $module}
            <h3>Модуль {$module->title} </h3>

            {include file="_corriculum/_workplan/contentModules/subform.sections.tpl"}
        {/foreach}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentModules/common.right.tpl"}
{/block}