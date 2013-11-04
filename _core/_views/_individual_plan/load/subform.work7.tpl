{if ($load->conclusion == "")}
    <div class="alert alert-block">
        Нет данных для отображения
    </div>
{else}
    {$load->conclusion}
{/if}