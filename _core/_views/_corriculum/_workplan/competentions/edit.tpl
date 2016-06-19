{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование компетенции</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/competentions/form.tpl"}
    
    <h4>Знания</h4>
    {CHtml::activeComponent("workplancompetentionknowledges.php?id={$object->getId()}", $object)}
    
    <h4>Умения</h4>
    {CHtml::activeComponent("workplancompetentionskills.php?id={$object->getId()}", $object)}
    
    <h4>Владения</h4>
    {CHtml::activeComponent("workplancompetentionexperiences.php?id={$object->getId()}", $object)}
    
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/competentions/common.right.tpl"}
{/block}