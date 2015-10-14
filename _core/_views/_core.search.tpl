<table border="0" width="100%">
	<tr>
		{block name="localSearchContent"}{/block}
	</tr>
    <tr>
        <td valign="top">
            <table cellspacing="2">
                {foreach $__search as $field=>$value}
                    <tr>
                        <td><b>{$field}:</b></td>
                        <td>{$value}</td>
                        <td><i class="icon-trash" style="cursor: pointer; " id="main_search_reset"></i></td>
                    </tr>
                {/foreach}
            </table>
        </td>
        <td valgin="top" width="300px">
            <input type="text" id="main_search_field" autocomplete="off" class="input-xlarge" />
        </td>
    </tr>
</table>
<br>

<script>
    jQuery(document).ready(function(){
        var searchResults = new Object();
        jQuery("#main_search_field").typeahead({
            source: function (query, process) {
                return jQuery.ajax({
                    url: "{$web_root}_modules/_search/",
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {
                        "query": query,
                        "action": "search",
                        "params": {
                            {if $__current_task != ""}
                            "__task": {$__current_task}
                            {/if}
                        }
                    },
                    beforeSend: function(){
                        /**
                         * Показываем индикатор активности
                         */
                        jQuery("#main_search_field").css({
                            "background-image": 'url({$web_root}images/ajax-loader.gif)',
                            "background-repeat": "no-repeat",
                            "background-position": "95% center"
                        });
                    },
                    success: function(data){
                        var lookup = new Array();
                        searchResults = new Object();
                        for (var i = 0; i < data.length; i++) {
                            lookup.push(data[i].label);
                            var searchObj = new Object();
                            searchObj.field = data[i].field;
                            searchObj.className = data[i].class;
                            searchObj.value = data[i].value;
                            searchResults[data[i].label] = searchObj;
                        }
                        process(lookup);
                        jQuery("#main_search_field").css("background-image", "none");
                    }
                });
            },
            updater: function(item){
                var value = searchResults[item].value;
                var key = searchResults[item].field;
                var className = searchResults[item].className;
                /**
                 * Делаем фильтр по указанному полю
                 */
                var url = window.location.origin + window.location.pathname;
                var params = new Array();
                if (window.location.search != "") {
                    var qw = window.location.search;
                    qw = qw.substr(1);
                    var parts = qw.split("&");
                    for (var i = 0; i < parts.length; i++) {
                        var param = parts[i].split("=");
                        if (param[0] !== "filter" && param[0] !== "filterClass" && param[0] !== "filterLabel") {
                            params[params.length] = param[0] + "=" + param[1];
                        }
                    }
                }
                params[params.length] = "filter=" + key + ":" + value;
                params[params.length] = "filterClass=" + className;
                params[params.length] = "filterLabel=" + item;
                /**
                 * Собираем строку запроса обратно
                 */
                url = url + "?" + params.join("&");
                /**
                 * Переадресация
                 */
                window.location.href = url;
            },
            minLength: 1,
            items: 20
        });
        jQuery("#main_search_reset").on("click", function(){
            /**
             * Сбрасываем фильтр
             */
            var url = window.location.origin + window.location.pathname;
            var params = new Array();
            if (window.location.search != "") {
                var qw = window.location.search;
                qw = qw.substr(1);
                var parts = qw.split("&");
                for (var i = 0; i < parts.length; i++) {
                    var param = parts[i].split("=");
                    if (param[0] !== "filter" && param[0] !== "filterClass" && param[0] !== "filterLabel") {
                        params[params.length] = param[0] + "=" + param[1];
                    }
                }
            }
            /**
             * Собираем строку запроса обратно
             */
            url = url + "?" + params.join("&");
            /**
             * Переадресация
             */
            window.location.href = url;
        });
    });
</script>