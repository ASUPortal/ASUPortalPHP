{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Студенты</h2>

    {CHtml::helpForCurrentPage()}

    <script>
        jQuery(document).ready(function(){
            var searchResults = new Object();
            jQuery("#search").typeahead({
                source: function (query, process) {
                    return jQuery.ajax({
                        url: "{$web_root}_modules/_students/",
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
                            jQuery("#search").css({
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
                            jQuery("#search").css("background-image", "none");
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
                items: 30
            });
        });
    </script>

    <table border="0" width="100%" class="tableBlank">
        <tr>
            <td valign="top">

            </td>
            <td valign="top" width="200px">
                <p>
                    <input type="text" id="search" style="width: 100%; " placeholder="Поиск">
                </p>
            </td>
        </tr>
    </table>

    <form action="index.php" method="post" id="MainView">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>{CHtml::activeViewGroupSelect("id", $students->getFirstItem(), true)}</th>
            <th>#</th>
            <th>{CHtml::tableOrder("fio", $students->getFirstItem())}</th>
            <th>{CHtml::tableOrder("stud_num", $students->getFirstItem())}</th>
            <th>{CHtml::tableOrder("group_id", $students->getFirstItem())}</th>
            <th>{CHtml::tableOrder("bud_contract", $students->getFirstItem())}</th>
            <th>{CHtml::tableOrder("telephone", $students->getFirstItem())}</th>
            <th>{CHtml::tableOrder("diploms", $students->getFirstItem())}</th>
            <th>Комментарий</th>
        </tr>
        {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $students->getItems() as $student}
        <tr>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить стедунта {$student->fio}')) { location.href='?action=delete&id={$student->id}'; }; return false;"></a></td>
            <td>{CHtml::activeViewGroupSelect("id", $student)}</td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$student->getId()}">{$student->getName()}</a></td>
            <td>{$student->stud_num}</td>
            <td>
                {if !is_null($student->getGroup())}
                    {$student->getGroup()->getName()}
                {/if}
            </td>
            <td>{$student->getMoneyForm()}</td>
            <td>{$student->telephone}</td>
            <td>
                {foreach $student->diploms->getItems() as $diplom}
                    <p><a href="{$web_root}_modules/_diploms/?action=edit&id={$diplom->getId()}">{$diplom->dipl_name}</a></p>
                {/foreach}
            </td>
            <td>{$student->comment}</td>
        </tr>
        {/foreach}
    </table>
    </form>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_students/common.right.tpl"}
{/block}