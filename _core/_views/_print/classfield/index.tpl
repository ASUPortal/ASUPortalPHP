{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Класс-описатели полей</h2>

    {CHtml::helpForCurrentPage()}
    
    {if $classes->getCount() == 0}
    	Нет классов-описателей
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Тип</th>
                <th>Использовать в документе</th>
                <th>Файл</th>
            </tr>
            {foreach $classes->getItems() as $class}
                <tr>
                    <td>{counter}</td>
                    <td>{$class->getFieldName()}&nbsp;</td>
                    <td>{$class->getFieldDescription()}&nbsp;</td>
                    <td>{$class->getFieldType()}&nbsp;</td>
                    <td>{get_class($class)}.class</td>
                    <td><textarea style="width: 90%;">{$class->getFilePath()}</textarea></td>
                </tr>
            {/foreach}
        </table>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_print/classfield/common.right.tpl"}
{/block}