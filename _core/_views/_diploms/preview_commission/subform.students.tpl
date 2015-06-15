{foreach $form->commission->getPreviewsListByDate() as $date=>$previews}
	<h2>{$date|date_format:"%d.%m.%Y"}</h2>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="5%">#</th>
        <th width="30%">Студент</th>
        <th>Тема ВКР</th>
    </tr>
    {foreach $previews as $preview} 
        <tr>
            <td>{counter}</td>
            <td>	
                {if !is_null($preview->student)}	
            		{$preview->student->getName()}
                {/if}
            </td>
            <td>	
				{if !is_null($preview->student)}
					<a href="{$web_root}_modules/_diploms/index.php?action=edit&id={CStaffManager::getDiplomByStudent($preview->student->id)->id}">{CStaffManager::getDiplomByStudent($preview->student->id)->dipl_name}</a>
                {/if}
            </td>
        </tr>
    {/foreach}
</table>	
{/foreach}