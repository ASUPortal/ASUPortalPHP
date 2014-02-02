{function name=menuItemsAsListWithCount level=0}
<ul class="{if $level == 0}nav{else}dropdown-menu{/if}">
    {foreach $data as $entry}
        {if ($entry->getChilds()->getCount() > 0)}
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="{$entry->getLink()|htmlspecialchars}">{$entry->getName()|htmlspecialchars}</a>
                {call name=menuItemsAsListWithCount data=$entry->getChilds()->getItems() level=$level+1}
            </li>
        {else}
            {if $level > 0 || $entry->getId() == 200000}
            <li>
                {if {$entry->getName()} == "<hr />"}
                    <a class="divider"></a>
                {else}
                <a href="{$entry->getLink()|htmlspecialchars}">{$entry->getName()|htmlspecialchars}</a>
                {/if}
            </li>
            {/if}
        {/if}
    {/foreach}
</ul>
{/function}

<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li>
                <p class="navbar-text">
                    <a href="#" id="asu_menu_hider" class="icon-th-list"></a>
                </p>
            </li>
            <li>&nbsp;&nbsp;&nbsp;</li>
            <li>
                <p class="navbar-text">
                    <a href="#" id="asu_quick_search" class="icon-search"></a>
                </p>
            </li>
        </ul>
        {call name=menuItemsAsListWithCount data=CMenuManager::getMenu("admin_menu")->getMenuPublishedItemsInHierarchy()->getItems()}
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        /**
         * Делаем из иконки с лупой выпадушку с поиском
         */
        jQuery("#asu_quick_search").popover({
            placement: "bottom",
            html: true,
            title: "Быстрый поиск",
            content: function(){
                return '<div style="width: 500px; " id="asu_quick_search_container">' +
                        '<div style="text-align: center; ">' +
                        '<img src="{$web_root}images/ajax-loader.gif" />' +
                        '</div>' +
                        '</div>';
            }
        });
        /**
         * После загрузки выпадушки начинаем грузить в нее форму
         * для поиска
         */
        jQuery("#asu_quick_search").on("shown", function(){
            /**
             * Увеличиваем размер
             */
            jQuery(this).siblings(".popover").css("max-width", "none");
            jQuery.ajax({
                url: web_root + "_modules/_search/",
                cache: false,
                data: {
                    action: "getGlobalSearchSubform"
                },
                success: function(data){
                    jQuery("#asu_quick_search_container").empty();
                    jQuery("#asu_quick_search_container").html(data);
                    /**
                     * Инициализируем поиск
                     */
                    jQuery("#lookupField").on("input", function(){
                        jQuery.ajax({
                            url: web_root + "_modules/_search/",
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
                }
            });
        });
    });
</script>