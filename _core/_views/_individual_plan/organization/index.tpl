{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Учебная и организационно-методическая работа</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("id", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("id_kadri", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("id_year", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("id_vidov_rabot", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("prim", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("srok_vipolneniya", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("kol_vo_plan", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("vid_otch", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("id_otmetka", $objects->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("comment", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить учебная и организационно-методическая работа')) { location.href='organizational.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="organizational.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->id}</td>
                    <td>{$object->id_kadri}</td>
                    <td>{$object->id_year}</td>
                    <td>{$object->id_vidov_rabot}</td>
                    <td>{$object->prim}</td>
                    <td>{$object->srok_vipolneniya}</td>
                    <td>{$object->kol_vo_plan}</td>
                    <td>{$object->vid_otch}</td>
                    <td>{$object->id_otmetka}</td>
                    <td>{$object->comment}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "organizational.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/organization/index.right.tpl"}
{/block}