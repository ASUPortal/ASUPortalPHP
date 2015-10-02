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
                    <th colspan="3">Аудиторная работа</th>
                    <th rowspan="2">СРС</th>
                </tr>
                <tr>
                    <th>Л</th>
                    <th>ПЗ</th>
                    <th>ЛР</th>
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