{extends file="_core.3col.tpl"}

{block name="localSearchContent"}
    <script>
    jQuery(document).ready(function(){
		$("#kadri_id").change(function(){
			if ($("#kadri_id").val() != 0) {
				window.location.href = "?action=index&filter=kadri_id:" + $("#kadri_id").val();
			}
		});
    });
	</script>
    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="person">Преподаватель</label>
            <div class="controls">
                {CHtml::dropDownList("kadri_id", CStaffManager::getPersonsList(), "", "kadri_id", "span12")}  
            </div>
        </div>
    </div>
{/block}

{block name="asu_center"}

<h2>Дипломные темы студентов</h2>
    {CHtml::helpForCurrentPage()}
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
	            <th>{CHtml::tableOrder("dipl_prew.date_preview", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("date_act", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("foreign_lang", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("protocol_2aspir_id", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("recenz_id", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("study_mark", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("gak_num", $diploms->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("comment", $diploms->getFirstItem())}</th>
	        </tr>
	        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $diploms->getItems() as $diplom}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить диплом {$diplom->dipl_name}')) { location.href='?action=delete&id={$diplom->id}'; }; return false;"></a></td>
	            <td>{CHtml::activeViewGroupSelect("id", $diplom)}</td>
	            <td>{counter}</td>
	            
	                {if !is_null($diplom->confirmation)}
	                	<td style="background-color:{$diplom->confirmation->color_mark}">
	                {else}
	                	<td>
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
	                {if $diplom->getLastPreviewDate() != "0"}
	                    {$diplom->getLastPreviewDate()|date_format:"d.m.Y"}
	                {/if}
	            </td>
	            <td>
	                {$diplom->date_act|date_format:"d.m.Y"}
	            </td>
	            <td>
	                {if !is_null($diplom->language)}
	                    {$diplom->language->getValue()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->recomendationProtocol)}
	                    {$diplom->recomendationProtocol->getNumber()} от {$diplom->recomendationProtocol->getDate()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->reviewer)}
	                    {$diplom->reviewer->getName()}
	                {/if}
	            </td>
	            <td>
	                {if !is_null($diplom->mark)}
	                    {$diplom->mark->getValue()}
	                {/if}
	            </td>
	            <td>
	                <a href="{$web_root}_modules/_state_attestation/?action=edit&id={$diplom->gak_num}">{$diplom->gak_num}</a>
	            </td>
	            <td>
	                {$diplom->comment}
	            </td>
	        </tr>
	        {/foreach}
	    </table>
	    </form>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
{include file="_diploms/index.right.tpl"}
{/block}
