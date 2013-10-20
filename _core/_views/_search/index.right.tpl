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
    <a onclick="updateIndex(); return false;">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-print-preview.png">
            Обновить индекс
        </center>
    </a>
</p>

<script>
    var toExport = new Array();
    function updateIndex() {
        /**
         * Сбрасываем прогресс
         */
        jQuery("#updateIndexProgress").css("width", "0%");
        /**
         * Обращаемся к серверу, получаем идентификаторы
         * выгружаемых моделей
         */
        jQuery.ajax({
            url: "{$web_root}_modules/_search/",
            type: "GET",
            cache: false,
            data: {
                "action": "getExportableModels"
            },
            dataType: "json",
            success: function(data){
                toExport = new Array();
                for (var i = 0; i < data.length; i++) {
                    toExport[i] = data[i];
                }
                if (toExport.length > 0) {
                    exportData(0);
                }
            }
        });
    };
    /**
     * Экспорт в поиск указанной модели
     *
     * @param index
     */
    function exportData(index) {
        jQuery.ajax({
            url: "{$web_root}_modules/_core/models.php",
            type: "GET",
            cache: false,
            data: {
                "action": "export",
                "id": toExport[index]
            },
            success: function(data){
                if (data != "1") {
                    alert(data);
                }
                /**
                 * Отмечаем прогресс
                 */
                var width = (100 / toExport.length) * (index + 1);
                jQuery("#updateIndexProgress").css("width", width + "%");
                if (toExport.length > (index + 1)) {
                    exportData(index + 1);
                }
            }
        });
    }
</script>