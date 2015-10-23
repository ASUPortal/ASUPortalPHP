{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if $objects->getCount() == 0}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th rowspan="2">Вид работы</th>
                <th colspan="{$terms->getCount() + 1}">Трудоемкость, часов</th>
            </tr>
            <tr>
                {foreach $terms as $term}
                    <td>{$term} семестр</td>
                {/foreach}
                <td>Всего</td>
            </tr>
            </thead>
            <tbody>
            {foreach $objects->getItems() as $array}
                <tr>
                    {foreach $array as $value}
                        <td>{$value}</td>
                    {/foreach}
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
    
    <h4>Виды контроля</h4>
    {if $controlTypes->getCount() == 0}
        Нет объектов для отображения
    {else}
    	<table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th rowspan="2" width="16">#</th>
                    <th rowspan="2">{CHtml::tableOrder("type_study_activity_id", $controlTypes->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("section_id", $controlTypes->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("control_id", $controlTypes->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("mark", $controlTypes->getFirstItem())}</th>
                    <th rowspan="2">{CHtml::tableOrder("amount_labors", $controlTypes->getFirstItem())}</th>
                    <th colspan="2">Баллы</th>
                </tr>
                <tr>
                    <th>{CHtml::tableOrder("min", $controlTypes->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("max", $controlTypes->getFirstItem())}</th>
            	</tr>
            </thead>
            <tbody>
            
            {foreach $controlTypes->getItems() as $object}
                <tr>
                    <td>{counter}</td>
                    <td>{$object->type}</td>
                    <td>{$object->section->name}</td>
                    <td>{$object->control}</td>
                    <td>{$object->mark}</td>
                    <td>{$object->amount_labors}</td>
                    <td>{$object->min}</td>
                    <td>{$object->max}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
      
    <h4>Описание и количество баллов по видам учебной деятельности</h4>
    {if $marks->getCount() == 0}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Вид учебной деятельности</th>
                <th>Описание</th>
            </tr>
            </thead>
            <tbody>
            {counter start=0 print=false}
            {foreach $marks->getItems() as $mark}
                <tr>
                    <td>{counter}</td>
                    <td>{$mark->type->getValue()}</td>
                    <td>
                    	{foreach $mark->marks->getItems() as $control}
                            <p>{$control->mark}</p>
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}

    {foreach $termSectionsData as $termId=>$termData}
        <h4>Разделы дисциплины, изучаемые в {CBaseManager::getWorkPlanTerm($termId)->number}-м семестре</h4>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th rowspan="3">№ раздела</th>
                    <th rowspan="3">Наименование раздела</th>
                    <th colspan="3">Количество часов</th>
                </tr>
                <tr>
                    <th rowspan="2">Всего</th>
                    <th colspan="4">Аудиторная работа</th>
                    <th rowspan="2">СРС</th>
                </tr>
                <tr>
                    <th>Л</th>
                    <th>ПЗ</th>
                    <th>ЛР</th>
                    <th>КСР</th>
                </tr>
            </thead>
            <tbody>
                {foreach $termData as $array}
                    <tr>
                        {foreach $array as $value}
                            <td>{$value}</td>
                        {/foreach}
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/foreach}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/content/common.right.tpl"}
{/block}