{extends file="_core.component.tpl"}

{block name="asu_center"}
	<form action="workplanstatus.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        {CHtml::hiddenField("action", "view")}
        {CHtml::hiddenField("id", $id)}
	    <table border="0" width="100%" class="tableBlank">
	        <tr>
	            <td valign="top">
	            	<p>
	            		<label for="templates">Выберите шаблон рабочей программы</label>
	            			{CHtml::dropDownList("template", $templates, "", "template")}
	            	</p>    
	            </td>
	        </tr>
	    </table>
	    <div class="control-group">
            <div class="controls">
                {CHtml::submit("Посмотреть", false)}
            </div>
        </div>
	</form>
{/block}

{block name="asu_right"}
	{include file="_corriculum/_workplan/status/common.right.tpl"}
{/block}