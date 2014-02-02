{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Глобальный поиск</h2>

    {CHtml::helpForCurrentPage()}

    {CHtml::textField("query", "", "lookupField", "", 'style="width: 100%; " placeholder="Введите фразу для поиска"')}

    <div id="results">

    </div>

    <script>
        jQuery(document).ready(function(){
            jQuery("#lookupField").on("input", function(){
                jQuery.ajax({
                    beforeSend: function(){
                        /**
                         * Показываем индикатор активности
                         */
                        jQuery("#lookupField").css({
                            "background-image": 'url({$web_root}images/ajax-loader.gif)',
                            "background-repeat": "no-repeat",
                            "background-position": "95% center"
                        });
                        jQuery("#results").css("opacity", "0.5");
                    },
                    cache: false,
                    data: {
                        action: "globalSearch",
                        keyword: jQuery("#lookupField").val()
                    },
                    success: function(data){
                        jQuery("#results").empty();
                        jQuery("#results").html(data);
                        jQuery("#lookupField").css("background-image", "none");
                        jQuery("#results").css("opacity", "1");
                    }
                });
            });
        });
    </script>
{/block}

{block name="asu_right"}
    {include file="_search/common.right.tpl"}
{/block}