{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Специальности</h2>

    {CHtml::helpForCurrentPage()}

<script>
    jQuery(document).ready(function(){
        jQuery("#search").autocomplete({
            source: web_root + "_modules/_specialities/?action=search",
            minLength: 2,
            select: function(event, ui) {
                if (ui.item.type == 1) {
                    // выбрана специальность
                    window.location.href = "?action=index&filter=speciality:" + ui.item.object_id;
                }
            }
        });
    });
    /**
     * Очистка указанного фильтра
     * @param type
     */
    function removeFilter(type) {
        var filters = new Object();
        {if !is_null($selectedSpeciality)}
            filters.speciality = {$selectedSpeciality->getId()};
        {/if}
        var action = "?action=index&filter=";
        var actions = new Array();
        jQuery.each(filters, function(key, value){
            if (key !== type) {
                actions[actions.length] = key + ":" + value;
            }
        });
        action = action + actions.join("_");
        window.location.href = action;
    }
</script>

<table border="0" width="100%" class="tableBlank">
    <tr>
        <td valign="top">
            <form>
                {if !is_null($selectedSpeciality)}
                    <p>
                        <label for="group">Специальность</label>
                        {$selectedSpeciality->getValue()}
                        <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('speciality'); return false; "/></span>
                    </p>
                {/if}
            </form>
        </td>
        <td valign="top" width="200px">
            <p>
                <input type="text" id="search" style="width: 100%; " placeholder="Поиск">
            </p>
        </td>
    </tr>
</table>

<table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("name", $specialities->getFirstItem())}</th>
            <th>Комментарий</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $specialities->getItems() as $speciality}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить специальность {$speciality->name}')) { location.href='?action=delete&id={$speciality->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$speciality->getId()}">{$speciality->name}</a></td>
            <td>{$speciality->comment}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_specialities/common.right.tpl"}
{/block}