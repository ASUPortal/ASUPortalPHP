<div><input name="" onclick="location.href='{$web_root}_modules/_question_add/index.php?action=index&user_id={$lect->getUser()->id}'" type="button" class="btn" value="Задать вопрос"></div>

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