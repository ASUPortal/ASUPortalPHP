<p>
    <a href="load.php?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Назад
        </center></a>
</p>

<p>
    <a href="#myModal" data-toggle="modal">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить
        </center></a>
</p>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Добавление записи в план</h3>
    </div>
    <div class="modal-body">
        <p><a href="load/organizational.php?action=add&id={$person->getId()}">Учебно и организационно-методическая работа</a></p>
        <p><a href="load/sciences.php?action=add&id={$person->getId()}">Научно-методическая и госбюджетая научно-исследовательская работа</a></p>
        <p><a href="load/educations.php?action=add&id={$person->getId()}">Учебно-воспитательная работа</a></p>
        <p><a href="load/publications.php?action=add&id={$person->getId()}">Научная работа</a></p>
        <p><a href="load/changes.php?action=add&id={$person->getId()}">Изменение в годовом индивидуальном плане</a></p>
        <p><a href="load/conclusions.php?action=add&id={$person->getId()}">Заключение заведующего кафедрой</a></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>

<p>
    <a href="#autoComplete" data-toggle="modal">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/view-refresh.png"><br>
            Автозаполнение
        </center></a>
</p>

<script>
    jQuery(document).ready(function(){
        jQuery("select[name=autocomplete_year_id]").on("change", function(){
            var year = jQuery(this).val();
            var person = {$person->getId()};

            getAutocompleteData(year, person);
        });
        jQuery("#addSelected").on("click", function(){
            var items = new Array();
            jQuery("#autocomplete-container").find("input[type=checkbox]:checked").each(function(key, value){
                items[items.length] = jQuery(value).val();
            });
            var data = {
                items: items,
                year: jQuery("select[name=autocomplete_year_id]").val(),
                person: {$person->getId()},
                action: "setAutocompleteDate"
            };
            jQuery.ajax({
                url: "{$web_root}_modules/_individual_plan/load.php",
                cache: false,
                data: data,
                type: "GET",
                beforeSend: function(){
                    jQuery("#overlay").css("display", "block");
                },
                success: function(){
                    document.location.reload();
                }
            });
        });
        function getAutocompleteData(year, person) {
            var data = {
                action: "getAutocompleteData",
                year: year,
                person: person
            };
            jQuery.ajax({
                url: "{$web_root}_modules/_individual_plan/load.php",
                cache: false,
                data: data,
                dataType: "html",
                type: "GET",
                success: function(data){
                    fillPreviewData(data);
                },
                beforeSend: function(){
                    jQuery("#autocomplete-container").html('<div style="text-align: center;"><img src="{$web_root}images/loader.gif"></div>');
                }
            });
        }
        function fillPreviewData(data) {
            jQuery("#autocomplete-container").html(data);
        }
    });
</script>

<div id="autoComplete" class="modal hide fade" tabindex="-1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Автозаполнение</h3>
    </div>
    <div class="modal-body">
        {CHtml::dropDownList("autocomplete_year_id", CTaxonomyManager::getYearsList())}
        <div id="autocomplete-container" style="height: 500px; overflow: scroll; ">

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        <button class="btn btn-primary" id="addSelected">Добавить выбранные</button>
    </div>
</div>