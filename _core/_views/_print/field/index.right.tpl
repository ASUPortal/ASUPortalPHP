<p>
    <a href="index.php?action=index">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/edit-undo.png"><br>
            Назад
        </center></a>
</p>

<p>
    <a href="field.php?action=add">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/list-add.png"><br>
            Добавить описатель поля
        </center></a>
</p>

<p>
    <a href="#" onclick="showExportDialog(); return false; ">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/format-indent-more.png"><br>
            Экспорт
        </center></a>
</p>

<p>
    <a href="#" onclick="showImportDialog(); return false;">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/format-indent-less.png"><br>
            Импорт
        </center></a>
</p>

<script>
    function showExportDialog() {
        jQuery("#export_dialog").dialog({
            width: 500,
            height: 500
        });
        jQuery("#loading").show();
        jQuery("#export_data").hide();
        var boxes = new Array();
        var selected = new Array();
        boxes = jQuery("#table").find("input[type=checkbox]:checked");
        jQuery.each(boxes, function(key, value) {
            selected[selected.length] = jQuery(value).val();
        });
        jQuery.ajax({
            url: "{$web_root}_modules/_print/field.php?action=export",
            data: {
                selected: selected
            },
            type: "post"
        }).done(function(data) {
            jQuery("#loading").hide("slow");
            jQuery("#export_data").show("slow");
            jQuery("#export_data").html(data);
        });
    }
    function showImportDialog() {
        jQuery('#import_dialog').dialog({
            width: 500, height: 500
        });
    }
</script>

<div id="export_dialog" title="Экспорт описателей полей" style="display: none;">
    <div id="loading">Выполняется экспорт, подождите <img src="{$web_root}images/autocomplete_indicator.gif"></div>
    <textarea id="export_data" style="display: none; width: 470px; height: 440px; "></textarea>
</div>

<div id="import_dialog" title="Импорт описателей полей" style="display: none;">
    <form action="field.php" method="post">
        <input type="hidden" name="action" value="import">
        <textarea id="import_data" style="width: 470px; height: 400px; " name="export_data"></textarea>
        <input type="submit" value="Импортировать">
    </form>
</div>