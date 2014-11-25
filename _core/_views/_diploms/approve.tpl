{extends file="_core.3col.tpl"}
{block name="asu_center"}

<h2>Утверждение тем ВКР</h2>
{include file="_core.searchLocal.tpl"}
    	
	{if $diploms->getCount() == 0}
		Нет дипломов для отображения
	{else}
		<form action="index.php" method="post" id="MainView">
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th>{CHtml::activeViewGroupSelect("id", $diploms->getFirstItem(), true)}</th>
	            <th>№</th>
	            <th>{CHtml::tableOrder("diplom_confirm", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("dipl_name", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("pract_place_id", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("prepod.fio", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("student.fio", $diploms->getFirstItem(),true)}</th>
	            <th>{CHtml::tableOrder("st_group.name", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("recenz_id", $diploms->getFirstItem())}</th>
	        </tr>
	        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $diploms->getItems() as $diplom}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить диплом {$diplom->dipl_name}')) { location.href='?action=delete&id={$diplom->id}'; }; return false;"></a></td>
	            <td>{CHtml::activeViewGroupSelect("id", $diplom)}</td>
	            <td>{counter}</td>

	            
	                {if !is_null($diplom->confirmation)}
	      
	                	<td style="background-color:{$diplom->confirmation->color_mark}">
	                	{assign var="type" value={$diplom->confirmation->id}}
	                	{if $type == '1'}	
                			<i class="icon-off confirmEditSwitch" id="{$diplom->getId()}"></i>
                		{elseif $type == '2'}
                			<i class="icon-ok confirmReformulSwitch" id="{$diplom->getId()}"></i>
                		{elseif $type == '3'}
                			<i class="icon-off confirmLookSwitch" id="{$diplom->getId()}"></i>
                		{elseif $type == '4'}
                			<i class="icon-ok confirmCancelSwitch" id="{$diplom->getId()}"></i>
	                	{/if}
	                {else}
	                	<td><i class="icon-ok confirmSwitch" id="{$diplom->getId()}"></i> 
	                {/if}
	            </td>
	            <td><a href="?action=edit&id={$diplom->getId()}">{$diplom->dipl_name}</a></td>                       
	            <td>
	                {if is_null($diplom->practPlace)}
	                    {$diplom->pract_place}
	                {else}
	                    {$diplom->practPlace->getValue()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->person)}
	                    <a href="{$web_root}_modules/_staff/?action=edit&id={$diplom->person->getId()}" title="о преподавателе">{$diplom->person->getName()}</a>
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->student)}
	                    <a href="{$web_root}_modules/_students/?action=edit&id={$diplom->student->getId()}" title="о студенте">{$diplom->student->getName()}</a>
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->student)}
	                    {if !is_null($diplom->student->getGroup())}
	                        {$diplom->student->getGroup()->getName()}
	                    {/if}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->reviewer)}
	                    {$diplom->reviewer->getName()}
	                {/if}
	            </td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
        {CHtml::paginator($paginator, "?action=index")}
        
    {/if}
<script>
    jQuery(document).ready(function(){
        jQuery(".confirmSwitch").css("cursor", "pointer");
        function onStateChange(that, action) {
            var id = jQuery(that).attr("id");
            var image = jQuery(that);
            jQuery.ajax({
                url: "{$web_root}_modules/_diploms/index.php",
                cache: false,
                type: "GET",
                async: true,
                data: {
                    "action": action,
                    "id": id
                },
                beforeSend: function() {
                    if (jQuery(image).hasClass("icon-ok")) {
                        jQuery(image).removeClass("icon-ok");
                    } else if (jQuery(image).hasClass("icon-off")) {
                        jQuery(image).removeClass("icon-off");
                    }
                    jQuery(image).addClass("icon-signal");
                },
                success: function(data){
                    $("#diplom_confirm").append(data);
                }
            });
        }
        jQuery(".confirmSwitch").on("click", function(){
            onStateChange(this, "ChangeConfirm");
		});
		jQuery(".confirmEditSwitch").on("click", function(){
            onStateChange(this, "ChangeConfirmEdit");
		});
		jQuery(".confirmReformulSwitch").on("click", function(){
            onStateChange(this, "ChangeConfirmReformul");
		});
		jQuery(".confirmLookSwitch").on("click", function(){
            onStateChange(this, "ChangeConfirmLook");
		});
		jQuery(".confirmCancelSwitch").on("click", function(){
            onStateChange(this, "ChangeConfirmCancel");
        });
    });
</script>
{/block}

{block name="asu_right"}
{include file="_diploms/index.right.tpl"}
{/block}


