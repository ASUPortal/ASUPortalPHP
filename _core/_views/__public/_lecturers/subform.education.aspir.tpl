{if ($lect->getAspirCurrent()->getCount() == 0)}
	аспирантов на портале нет
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
	<tr>
	          <th>#</th>
	          <th>ФИО</th>
	          <th>Тема диссертации</th>
	</tr>
{$i = 1}
{foreach $lect->getAspirCurrent()->getItems() as $aspir}
	<tr>
	   	<td>{$i++}</td>
	   	<td>{$aspir->fio}</td>
	   	<td>{$aspir->tema}</td>
	</tr>
{/foreach}
</table>
{/if}