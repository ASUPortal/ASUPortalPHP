{extends file="_core.flow.tpl"}
{block name="content"}
<div class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Выбор из списка</h3>
    </div>
    <div class="modal-body">
        {foreach $items->getItems() as $key=>$value}
            {if $multiple}
                <label class="checkbox">
                    <input type="checkbox" value="{$key}" name="selected[]"/>
                    {$value}
                </label>
            {else}
                <label class="radio">
                    <input type="radio" value="{$key}" name="selected[]"/>
                    {$value}
                </label>
            {/if}
        {/foreach}
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        <button class="btn btn-primary">Выбрать</button>
    </div>
</div>
{/block}