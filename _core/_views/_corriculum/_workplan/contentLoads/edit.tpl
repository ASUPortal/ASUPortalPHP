{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование нагрузки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentLoads/form.tpl"}

    {CHtml::activeComponent("workplancontenttopics.php?load_id={$object->getId()}", $object)}

    {CHtml::activeComponent("workplancontenttechnologies.php?load_id={$object->getId()}", $object)}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentLoads/common.right.tpl"}
{/block}