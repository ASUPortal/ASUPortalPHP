{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Рабочие программы</h2>
    {CHtml::helpForCurrentPage()}
    
    {if $plans->getCount() == 0}
		Нет планов для отображения
	{else}
		<form action="index.php" method="post" id="MainView">
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            <th><input type="checkbox" id="selectAll"></th>
	            <th>№</th>
	            <th>{CHtml::tableOrder("diplom_confirm", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("dipl_name", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("pract_place_id", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("prepod.fio", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("student.fio", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("st_group.name", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("dipl_prew.date_preview", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("date_act", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("foreign_lang", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("protocol_2aspir_id", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("recenz_id", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("study_mark", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("gak_num", $diploms->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("comment", $diploms->getFirstItem(), true)}</th>
	        </tr>
	        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
	        {foreach $diploms->getItems() as $diplom}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить тему ВКР {$diplom->dipl_name}')) { location.href='?action=delete&id={$diplom->id}'; }; return false;"></a></td>
	            <td>
                    <input type="checkbox" value="{$diplom->getId()}" name="selectedDoc[]">
                </td>
	            <td>{counter}</td>
				<td>
                    <span>
                        <span class="approveTheme" asu-id="{$diplom->getId()}" asu-color="{if is_null($diplom->confirmation)}white{else}{$diplom->confirmation->color_mark}{/if}">
                            {if is_null($diplom->confirmation)}
                                Не рассматривали
                            {else}
                                {$diplom->confirmation->getValue()}
                            {/if}
                        </span>
                    </span>
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
{include file="_workplan/workplan/common.right.tpl"}
{/block}
