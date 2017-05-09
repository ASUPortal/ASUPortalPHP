{extends file="_core.component.tpl"}

{block name="asu_center"}
	{if ($discipline->books->getCount() == 0)}
	    Нет учебников для отображения
	{else}
		<form action="books.php" method="post" id="Books">
		    <table class="table table-striped table-bordered table-hover table-condensed">
		        <tr>
		            <th>&nbsp;</th>
		            <th>{CHtml::activeViewGroupSelect("id", $discipline->books->getFirstItem(), true)}</th>
		            <th>#</th>
		            <th>Название книги</th>
		        </tr>
		        {counter start=0 print=false}
		        {foreach $discipline->books->getItems() as $book}
		            <tr>
		                <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить книгу?')) { location.href='books.php?action=delete&id={$book->getId()}&discipline_id={CRequest::getInt("discipline_id")}'; }; return false;"></a></td>
		                <td>{CHtml::activeViewGroupSelect("id", $book)}</td>
		                <td>{counter}</td>
		                <td><a href="books.php?action=edit&id={$book->getId()}&discipline_id={CRequest::getInt("discipline_id")}">{$book->book_name}</a></td>
		            </tr>
		        {/foreach}
		    </table>
		</form>
	{/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_books/common.right.tpl"}
{/block}