{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование показателя</h2>

    {CHtml::helpForCurrentPage()}

    <form action="indexes.php" method="post">
    <input type="hidden" name="action" value="save">
    {CHtml::activeHiddenField("id", $index)}

    <p>
        {CHtml::activeLabel("title", $index)}
        {CHtml::activeTextField("title", $index)}
        {CHtml::error("title", $index)}
    </p>

    <p>
        {CHtml::activeLabel("year_id", $index)}
        {CHtml::activeDropDownList("year_id", $index, CTaxonomyManager::getYearsList())}
        {CHtml::error("year_id", $index)}
    </p>

    <div id="system_properties" style="display: none; ">
    <p>
        {CHtml::activeLabel("manager_class", $index)}
        {CHtml::activeTextField("manager_class", $index)}
        {CHtml::error("manager_class", $index)}
    </p>

    <p>
        {CHtml::activeLabel("manager_method", $index)}
        {CHtml::activeTextField("manager_method", $index)}
        {CHtml::error("manager_method", $index)}
    </p>

    <p>
        {CHtml::activeLabel("person_method", $index)}
        {CHtml::activeTextField("person_method", $index)}
        {CHtml::error("person_method", $index)}
    </p>

    <p>
        {CHtml::activeLabel("isMultivalue", $index)}
        {CHtml::activeTextField("isMultivalue", $index)}
        {CHtml::error("isMultivalue", $index)}
    </p>
    </div>

    {if !is_null($index->id)}
        <table width="100%" id="index_values_table">
            <tr>
                <th></th>
                <th>Название показателя</th>
                <th>Вес</th>
                <th>[+]</th>
            </tr>
            {foreach $forms as $form}
            {CHtml::activeHiddenField("edit_title", $form, $form->id)}
            <tr>
                <td>
                    <a href="indexes.php?action=deleteValue&id={$form->id}" onclick="if (!confirm('Вы действительно хотите удалить показатель ')){ return false }">
                        <img src="{$web_root}images/todelete.png">
                    </a>
                </td>
                <td>
                    {if $form->edit_title == 1}
                        {CHtml::activeTextField("title", $form, "", "", 'style="width: 90%; "', $form->id)}
                    {else}
                        {$form->title}
                    {/if}
                </td>
                <td>{CHtml::activeTextField("value", $form, "", "", "", $form->id)}</td>
                <td align="center"><a href="#" onclick="jQuery('#index_{$form->id}').show(); return false;">[+]</a></td>
            </tr>
                <tr id="index_{$form->id}" style="display: none;">
                    <td>{CHtml::activeDropDownList("evaluate_method", $form, $evaluation_methods, "", "", "", $form->id)}</td>
                    <td valign="top">{CHtml::activeTextBox("evaluate_code", $form, "", "", 'style="width: 100%; height: 250px; "', $form->id)}</td>
                    <td><a href="#" onclick="jQuery('#index_{$form->id}').hide(); return false; ">[-]</a></td>
                </tr>
            {/foreach}
        </table>
    {/if}

    {CHtml::submit("Сохранить")}

    </form>
{/block}

{block name="asu_right"}
{include file="_rating/index/edit.right.tpl"}
{/block}