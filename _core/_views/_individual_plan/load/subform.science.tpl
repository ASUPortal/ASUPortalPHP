{if $load->getScienceLoad()->getCount() == 0}
    Нет записей о научно-методической работе
{else}
    <table class="table">
        <thead>
        <tr>
            <th width="16">&nbsp;</th>
            <th width="16">#</th>
            <th width="16">&nbsp;</th>
            <th>{CHtml::tableOrder("id_vidov_rabot", $load->getScienceLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("prim", $load->getScienceLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("srok_vipolneniya", $load->getScienceLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kol_vo_plan", $load->getScienceLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("vid_otch", $load->getScienceLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kol_vo", $load->getScienceLoad()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $load->getScienceLoad()->getFirstItem())}</th>
        </tr>
        </thead>
        {foreach $load->getScienceLoad()->getItems() as $object}
            <tr>
                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить научно-исследовательская работа')) { location.href='load/sciences.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                <td>{counter start=1}</td>
                <td><a href="load/sciences.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                <td>
                    {if !is_null($object->worktype)}
                        {$object->worktype->name}
                    {/if}
                </td>
                <td>{$object->prim}</td>
                <td>{$object->srok_vipolneniya}</td>
                <td>{$object->kol_vo_plan}</td>
                <td>{$object->vid_otch}</td>
                <td>{$object->kol_vo}</td>
                <td>{$object->comment}</td>
            </tr>
        {/foreach}
    </table>
{/if}