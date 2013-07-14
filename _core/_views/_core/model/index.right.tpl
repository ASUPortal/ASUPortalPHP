{if !is_null(CSession::getCurrentUser()->getPersonalSettings())}{if CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()}
    <p>
        <a href="{$web_root}_modules/_dashboard/">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-session.png"><br>
                На рабочий стол
            </center></a>
    </p>
{/if}{/if}

<p>
    <a href="?action=add"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png">
            Добавить модель
        </center></a>
</p>

<p>
    <a href="#notification" role="button" data-toggle="modal"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-save-as.png">
        Загрузить модели
    </center></a>
</p>

<div id="notification" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Импорт моделей</h3>
    </div>
    <div class="modal-body">
        <p>Модели, уже введенные в систему будут сохранены.</p>
        <p>Названия полей будут добавлены в язык по умолчанию.</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        <a href="?action=import" class="btn btn-primary">Импортировать</a>
    </div>
</div>