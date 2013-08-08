{if $load->getEducationLoad()->getCount() == 0}
    Нет записей об учебно-воспитательной работе
{else}
    <table class="table">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("id_vidov_rabot", $load->getEducationLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("id_study_groups", $load->getEducationLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("prim", $load->getEducationLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("srok_vipolneniya", $load->getEducationLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kol_vo_plan", $load->getEducationLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("id_otmetka", $load->getEducationLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $load->getEducationLoad()->getFirstItem())}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $load->getEducationLoad()->getItems() as $object}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить учебно-воспитательную работу')) { location.href='load/educations.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                <td>{counter start=1}</td>
                <td><a href="load/educations.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                <td>
                    {if !is_null($object->worktype)}
                        {$object->worktype->name}
                    {/if}
                </td>
                <td>
                    {if !is_null($object->group)}
                        {$object->group->getName()}
                    {/if}
                </td>
                <td>{$object->prim}</td>
                <td>{$object->srok_vipolneniya}</td>
                <td>{$object->kol_vo_plan}</td>
                <td>{$object->getMark()}</td>
                <td>{$object->comment}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}