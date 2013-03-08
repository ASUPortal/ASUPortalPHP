{extends file="_core.3col.tpl"}

{block name="asu_left"}

{/block}

{block name="asu_center"}
<h2>{$person->getName()}</h2>
    <form action="index.php" method="post">
        {CHtml::hiddenField("id", {$person->getId()})}
        {CHtml::hiddenField("action", "managePersonSave")}

        <p>
            {CHtml::label("Руководитель", "manager_id")}
            {CHtml::dropDownList("manager_id", CStaffManager::getPersonsListWithType("профессорско-преподавательский состав"), $person->getManagerId())}
        </p>

        <p>
            {CHtml::label("Роль на кафедре", "department_role_id")}
            {CHtml::dropDownList("department_role_id", CTaxonomyManager::getTaxonomy("department_roles")->getTermsList(), $person->getRoleId())}
        </p>

        <p>
            {CHtml::submit("Сохранить")}
        </p>
    </form>
{/block}

{block name="asu_right"}

{/block}