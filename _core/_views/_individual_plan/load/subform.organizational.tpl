{if $load->getOrganizationalLoad()->getCount() == 0}
    Нет записей об учебной и организационно-методической работе
{else}
    <table class="table">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("id_vidov_rabot", $load->getOrganizationalLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("prim", $load->getOrganizationalLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("srok_vipolneniya", $load->getOrganizationalLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kol_vo_plan", $load->getOrganizationalLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("vid_otch", $load->getOrganizationalLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("id_otmetka", $load->getOrganizationalLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $load->getOrganizationalLoad()->getFirstItem())}</th>
        </tr>
        </thead>
        {foreach $load->getOrganizationalLoad()->getItems() as $object}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить учебная и организационно-методическая работа')) { location.href='load/organizational.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                <td>{counter}</td>
                <td><a href="load/organizational.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                <td>
                    {if !is_null($object->worktype)}
                        {$object->worktype->name}
                    {/if}
                </td>
                <td>{$object->prim}</td>
                <td>{$object->srok_vipolneniya}</td>
                <td>{$object->kol_vo_plan}</td>
                <td>{$object->vid_otch}</td>
                <td>{$object->getMark()}</td>
                <td>{$object->comment}</td>
            </tr>
        {/foreach}
    </table>
{/if}