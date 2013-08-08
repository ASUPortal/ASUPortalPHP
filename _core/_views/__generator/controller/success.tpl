{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Генератор модулей</h2>

    {CHtml::helpForCurrentPage()}

    <p>Успешно сгенерированы следующие файлы:</p>
    {foreach $files as $file}
        <code>{$file}</code>
    {/foreach}
{/block}

{block name="asu_right"}
    {include file="__generator/controller/success.right.tpl"}
{/block}