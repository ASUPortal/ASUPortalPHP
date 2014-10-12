{extends file="_core.flow.tpl"}
{block name="content"}
    <script>
        jQuery(document).ready(function(){
            jQuery("#year_selector").on("change", function(){
                // загрузим список индивидуальных планов по выбранному году
                var ajax = jQuery.ajax({
                    url: web_root + "_modules/_flow/",
                    cache: false,
                    dataType: "json",
                    data: {
                        targetClass: "CStaffPrintController",
                        targetMethod: "GetIndividualPlansByYear",
                        beanId: jQuery("#flowDialogPlaceholder input[name=beanId]").val(),
                        year: jQuery(this).val(),
                        flow: true
                    },
                    success: function(data){
                        var container = jQuery("#plan_selector");
                        jQuery(container).empty();
                        jQuery.each(data, function(key, value){
                            jQuery(container).append(new Option(value, key));
                        });
                    }
                });
            });
        });
    </script>

    <div class="modal hide fade">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Параметры печати</h3>
        </div>
        <div class="modal-body">
            <p>
                {CHtml::activeLabel("year", $object)}
                {CHtml::activeDropDownList("year", $object, $object->getYears(), "year_selector")}
                {CHtml::error("year", $object)}
            </p>

            <p>
                {CHtml::activeLabel("plan", $object)}
                {CHtml::activeDropDownList("plan", $object, $object->getPlans(), "plan_selector")}
                {CHtml::error("plan", $object)}
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            <button class="btn btn-primary">Выбрать</button>
        </div>
    </div>
{/block}