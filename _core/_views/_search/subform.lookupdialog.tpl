<div id="lookupDialog" class="modal hide fade" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <p><b>Выбор из словаря или справочника</b></p>
    </div>
    <div class="modal-body">

    </div>
    <div class="modal-footer">
        {if $allowCreation == "true"}
            <span class="btn" asu-action="create">Добавить</span>
        {/if}
        <span class="btn" data-dismiss="modal" aria-hidden="true">Отмена</span>
        <span class="btn btn-primary" asu-action="ok">ОК</span>
    </div>
</div>