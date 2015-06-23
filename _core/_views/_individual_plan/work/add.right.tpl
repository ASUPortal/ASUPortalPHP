{CHtml::displayActionsMenu($_actions_menu)}
{if $object->work_type == "1"}
    <p>
        <a href="#autofill" data-toggle="modal">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/actions/view-refresh.png"><br>
                Заполнить план
            </center>
        </a>
    </p>

    <p>
        <a href="#" onClick="autoPropogate(); return false; ">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/categories/applications-system.png"><br>
                Распределить нагрузку
            </center>
        </a>
    </p>

    <div class="modal hide fade" id="autofill">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Заполнение плана</h3>
        </div>
        <div class="modal-body">
            {include file="_individual_plan/work/subform.fillLoad.tpl"}
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
            <a href="#" class="btn btn-primary" id="autoFillLoadGo">Заполнить</a>
        </div>
    </div>
{/if}