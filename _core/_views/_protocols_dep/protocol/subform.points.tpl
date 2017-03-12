{if $protocolPoints->getItems() == 0}
    Решения еще не добавлены
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tbody>
        {foreach $protocolPoints->getItems() as $point}
            <tr>
                <td rowspan="2">{$point->section_id}</td>
                <td><strong>Слушали:</strong></td>
                <td><b>{$point->person->fio_short}</b>
	                {$point->text_content}
                </td>
            </tr>
            <tr>
                <td><strong>Постановили:</strong></td>
                <td>
                    {if !is_null($point->opinion)}
                        {$point->opinion->getValue()}
                    {/if}
                    {$point->opinion_text}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}