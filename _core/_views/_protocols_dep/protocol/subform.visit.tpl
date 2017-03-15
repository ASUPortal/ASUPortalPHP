{if $protocol->visits->getCount() == 0}
    Нет объектов для отображения
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tbody>
            <tr>
                <td><strong>Присутствовали:</strong></td>
                <td>
                	{foreach $protocol->visits->getItems() as $visit}
                		{if $visit->visit_type != 0}
	                		{$visit->person->fio_short};
	                	{/if}
                	{/foreach}
                </td>
            </tr>
            <tr>
                <td><strong>Отсутствовали:</strong></td>
                <td>
                	{foreach $protocol->visits->getItems() as $visit}
	                    {if $visit->visit_type == 0 and $visit->matter_text != ""}
	                        {$visit->person->fio_short}
	                        ({$visit->matter_text});
	                    {/if}
	                    {if $visit->visit_type == 0}
	                        {$visit->person->fio_short};
	                    {/if}
					{/foreach}
                </td>
            </tr>
        
        </tbody>
    </table>
{/if}