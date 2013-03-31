<script>
    jQuery(document).ready(function(){
        jQuery("#search").autocomplete({
            source: web_root + "_modules/_library/?action=search",
            minLength: 2,
            select: function(event, ui) {
                if (ui.item.type == 1) {
                    // выбрана дисциплина
                    window.location.href = "?action=index&filter=subject:" + ui.item.object_id;
                } else if (ui.item.type == 2) {
                    // выбран преподаватель
                    window.location.href = "?action=index&filter=author:" + ui.item.object_id;
                } else if (ui.item.type == 3) {
                    // выбрана конкретная дисциплина
                    window.location.href = "?action=view&id=" + ui.item.object_id;
                }
            }
        });
    });
</script>

<table width="100%" border="0" class="tableBlank">
    <tr><td>
        <input type="text" id="search" style="width: 98%; " placeholder="Поиск учебных материалов">
    </td></tr>
</table>