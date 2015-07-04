<div style="font-weight:bold;"><a href="{$web_root}_modules/_question_add/index.php?action=index&user_id={$lect->getUser()->id}">Задать вопрос</a></div>
<br>
{if ($lect->getQuestions()->getCount() == 0)}
	вопросов с ответами на портале нет
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
	<tr>
	          <th>#</th>
	          <th>Вопрос</th>
	          <th>Ответ</th>
	</tr>
{$i = 1}
{foreach $lect->getQuestions()->getItems() as $quest}
	<tr>
	   	<td>{$i++}</td>
	   	<td>{$quest->question_text}</td>
	   	<td>{$quest->answer_text}</td>
	</tr>
{/foreach}
</table>
{/if}