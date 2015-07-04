{if ($form->person->getBiography()->getCount() == 0)}
		У Вас ещё не заполнена биография!
		<div><input name="" onclick="location.href='{$web_root}_modules/_biography/index.php?action=add'" type="button" class="btn" value="Добавить"></div>
{else}
        <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
	            <th width="10"></th>
	            <th width="100"><i class="icon-camera"></i></th>
	            <th>Биография</th>
        	</tr>
            {foreach $form->person->getBiography()->getItems() as $biography}
                {if !is_null($biography->getUser())}
					<b>{$biography->getUser()->getName()}</b><br><br>
				{/if}
                <tr>
                    <td><a href="{$web_root}_modules/_biography/index.php?action=edit&id={$biography->getId()}" class="icon-pencil" title="правка"></a></td>
					<td>{CHtml::activeAttachPreview("image", $biography, 100)}</td>
					<td>{$biography->main_text}</td>
                </tr>
            {/foreach}
        </table>
{/if}