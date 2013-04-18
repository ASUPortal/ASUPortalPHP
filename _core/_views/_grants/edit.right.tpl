<p>
    <a href="?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Все гранты
        </center></a>
</p>

<p>
    <a href="#" onclick="uploadFile(); return false;">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/devices/media-floppy.png"><br>
            Добавить файл
        </center></a>
</p>

<script>
    function uploadFile() {
        jQuery("<div>").dialog({
            modal: true,
            title: "Загрузка файлов",
            open: function(){
                jQuery(this).load(web_root + "_modules/_grants/?action=getuploadform&id={$form->grant->getId()}", function(){
                    /**
                     * В этот момент форма подгрузилась,
                     * навешиваем на нее события
                     */
                    jQuery("#fileupload").ajaxForm({
                        url: web_root + "_modules/_grants/"
                    });
                });
            }
        });
        return false;
    }
</script>