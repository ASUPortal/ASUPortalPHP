{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Учебные материалы</h2>
	{if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
    <script>
    jQuery(document).ready(function(){
        /**
         * Это старый код адаптированный под Bootstrap
         * Он не страшный, он оставлен таким, какой он есть, чтобы бэкэнд
         * не переписывать
         **/
        var searchResults = new Object();
        jQuery("#searchs").typeahead({
            source: function (query, process) {
                return jQuery.ajax({
                    url: "#",
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {
                        "query": query,
                        "action": "search"
                    },
                    beforeSend: function(){
                        /**
                         * Показываем индикатор активности
                         */
                        jQuery("#searchs").css({
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
                            searchResults[data[i].label] = data[i];
                        }
                        process(lookup);
                        jQuery("#searchs").css("background-image", "none");
                    }
                });
            },
            updater: function(item){
                var selected = searchResults[item];
                if (selected.type == 1) {
                    // выбрана дисциплина
                    window.location.href = "?action=index&filter=subject:" + selected.object_id;
                } else if (selected.type == 2) {
                    // выбран преподаватель
                    window.location.href = "?action=index&filter=author:" + selected.object_id;
                } else if (selected.type == 3) {
                    // выбрана конкретная дисциплина
                    window.location.href = "?action=publicView&id=" + selected.object_id;
                }
            }
        });
    });
	</script>
	<table border="0" width="100%" class="tableBlank">
		<tr>
			<td width="70%"></td>
      		<td>
				<p>
					<input type="text" id="searchs" style="width: 96%; " placeholder="Поиск">
				</p>
			</td>
		</tr>
	</table>

    {if ($folders->getCount() == 0)}
        Нет учебных материалов.
    {else}
        {include file="_library/public/public.subform.alphabet.tpl"}
        {include file="_library/public/public.subform.latest.tpl"}
        {include file="_library/public/public.subform.list.tpl"}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_library/public/public.index.right.tpl"}
{/block}