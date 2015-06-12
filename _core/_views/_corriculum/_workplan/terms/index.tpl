{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($plan->terms->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th rowspan="2">Вид работы</th>
                    <th colspan="{$plan->terms->getCount()}">Трудоемкость</th>
                </tr>
                <tr>
                    {foreach $plan->terms->getItems() as $t}
                        <th>
                            <a href="workplanterms.php?action=edit&id={$t->getId()}">{$t->number}</a>
                        </th>
                    {/foreach}
                </tr>
            </thead>
            <tbody>
                {foreach $data as $type=>$row}
                    <tr>
                        <td>{$type}</td>
                        {foreach $row as $col}
                            <td>{$col}</td>
                        {/foreach}
                    </tr>
                {/foreach}
            </tbody>
        </table>

        {foreach $termData as $term=>$data}
            <p></p><b>Разделы дисциплины, изучаемые в {$term} семестре</b></p>
            {if (empty($data))}
                Нет объектов для отображения
            {else}
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th rowspan="2">Наименование раздела</th>
                            <th colspan="
                            {$isFirst = true}
                            {foreach $data as $section=>$row}
                                {if $isFirst}
                                        {count($row)}
                                    {/if}
                                {$isFirst = false}
                            {/foreach}
                            ">Количество часов</th>
                        </tr>
                        <tr>
                            {$isFirst = true}
                            {foreach $data as $section=>$row}
                                {foreach $row as $title=>$col}
                                    {if $isFirst}
                                        <th>{$title}</th>
                                    {/if}
                                {/foreach}
                                {$isFirst = false}
                            {/foreach}
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $data as $section=>$row}
                            <tr>
                                <td>{$section}</td>
                                {foreach $row as $col}
                                    <td>{$col}</td>
                                {/foreach}
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {/if}
        {/foreach}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/terms/common.right.tpl"}
{/block}