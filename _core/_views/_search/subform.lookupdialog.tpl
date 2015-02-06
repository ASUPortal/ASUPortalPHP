<div id="lookupDialog" class="modal hide fade" role="dialog" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <p><b>Выбор из словаря или справочника</b></p>
    </div>
    <div class="modal-body" style="max-height: 300px; ">

    </div>
    <div class="modal-footer row-fluid" style="width: auto;">
        <div class="span8">
            <div class="modal-properties-box">Загрузка...</div>
        </div>
        <div class="span4">
            {if $allowCreation == "true"}
                <span class="btn" asu-action="create">Добавить</span>
            {/if}
            <span class="btn" data-dismiss="modal" aria-hidden="true">Отмена</span>
            <span class="btn btn-primary" asu-action="ok">ОК</span>
        </div>
    </div>
</div>