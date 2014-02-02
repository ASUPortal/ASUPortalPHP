{foreach $results as $result}
    <div class="alert alert-info">
        {if count($result["tasks"]) == 0}
            <p><b>{$result["text"]}</b></p>
        {elseif (count($result["tasks"]) == 1)}
            {foreach $result["tasks"] as $url=>$title}
                <p><b><a href="{$web_root}{$url}" target="_blank">{$result["text"]}</a></b></p>
                <p>
                    В задаче: <span class="label label-inverse"><a href="{$web_root}{$url}" target="_blank">{$title}</a></span>
                </p>
            {/foreach}
        {else}
            <p><b>{$result["text"]}</b></p>
            <p>
                В задачах:
                {foreach $result["tasks"] as $url=>$title}
                    <span class="label label-inverse"><a href="{$web_root}{$url}" target="_blank">{$title}</a></span>
                {/foreach}
            </p>
        {/if}
    </div>
{/foreach}