{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование раздела дисциплины</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/contentSections/form.tpl"}

    {CHtml::activeComponent("workplancontentlectures.php?section_id={$object->getId()}", $object)}

    {CHtml::activeComponent("workplancontentcontrols.php?section_id={$object->getId()}", $object)}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/contentSections/common.right.tpl"}
{/block}