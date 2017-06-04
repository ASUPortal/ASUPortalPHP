{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Шаблоны документов</h2>
{CHtml::helpForCurrentPage()}

    <script>
	    function removeFilter() {
	        var action = "?action=index";
	        window.location.href = action;
	    }
        jQuery(document).ready(function(){
            jQuery("#formset").on("change", function(){
                window.location.href="form.php?action=index&filter=formset:" + this.value;
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
                            <span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter(); return false; "/></span>
                        {/if}
                    </p>
                </form>
            </td>
            <td valign="top" width="200px">
            </td>
        </tr>
    </table>

    <table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>&nbsp;</th>
        <th>#</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Набор форм</th>
        <th>Активен</th>
        <th></th>
    </tr>
	{counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
    {foreach $forms->getItems() as $form}
        <tr>
            <td valign="top"><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить шаблон {$form->title}')) { location.href='?action=delete&id={$form->id}'; }; return false;"></a></td>
            <td valign="top">{counter}</td>
            <td valign="top"><a href="form.php?action=edit&id={$form->id}">{$form->title}<a/> ({$form->alias})</td>
            <td valign="top">{$form->description|nl2br}</td>
            <td valign="top">{if !is_null($form->formset)}{$form->formset->title}{else}{/if}</td>
            <td valign="top">{if $form->isActive == 1}Да{else}Нет{/if}</td>
            <td><input type="checkbox" name="selected[]" value="{$form->getId()}"></td>
        </tr>
    {/foreach}
</table>

{CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_print/form/index.right.tpl"}
{/block}