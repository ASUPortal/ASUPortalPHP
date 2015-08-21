{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Описатели полей</h2>
{CHtml::helpForCurrentPage()}

    <script>
        jQuery(document).ready(function(){
            jQuery("#formset").on("change", function(){
                window.location.href="field.php?action=index&filter=formset:" + this.value;
            });
        });
    </script>

    <table border="0" width="100%" class="tableBlank">
        <tr>
            <td valign="top">
                <form id="filters">
                    <p>
                        <label for="formset">Набор шаблонов</label>
                        {CHtml::dropDownList("formsets", $formsets, $selectedFormset, "formset")}
                        {if !is_null($selectedFormset)}
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('formset'); return false; "/></span>
                        {/if}
                    </p>
                    {if !is_null($selectedField)}
                        <p>
                            <label for="field">Поле</label>
                            <input type="hidden" name="field" value="{$selectedField->getId()}">
                            {$selectedField->title} ({$selectedField->alias})
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter('field'); return false; "/></span>
                        </p>
                    {/if}
                </form>
            </td>
            <td valign="top" width="200px">
            </td>
        </tr>
    </table>

    {include file="_core.searchLocal.tpl"}

    <table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>&nbsp;</th>
        <th>#</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Набор форм</th>
        <th></th>
    </tr>

    {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $fields->getItems() as $field}
        <tr>
            <td valign="top"><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить описатель поля {$field->title}')) { location.href='?action=delete&id={$field->id}'; }; return false;"></a></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="field.php?action=edit&id={$field->id}">{$field->title}<a/> ({$field->alias})</td>
            <td valign="top">{$field->description|nl2br}</td>
            <td valign="top">{$field->formset->title}</td>
            <td><input type="checkbox" name="selected[]" value="{$field->getId()}"></td>
        </tr>
    {/foreach}
</table>

{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_print/field/index.right.tpl"}
{/block}