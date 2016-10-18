{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Обновление файлового индекса</h2>

    {CHtml::helpForCurrentPage()}
    
    <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                	<th width="16">#</th>
                    <th>Результат индексации</th>
                </tr>
            </thead>
            <tbody>
            {counter start=0 print=false}
            {foreach $messages as $message}
                <tr>
                	<td>{counter}</td>
                    <td>{$message}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

{/block}

{block name="asu_right"}
    {include file="_search/common.right.tpl"}
{/block}