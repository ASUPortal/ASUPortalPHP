{extends file="_core.flow.tpl"}
{block name="content"}
    <div class="modal hide fade">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Сообщение</h3>
        </div>
        <div class="modal-body">
            {$message}
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary">ОК</button>
        </div>
    </div>
{/block}