{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>{$title}</h2>
    {CHtml::helpForCurrentPage()}

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table border="0" width="100%" class="tableBlank">
            <tr>
                <td valign="top">

                </td>
                <td valign="top" width="300px">
                    <p>
                        <input type="text" id="search" style="width: 95%; " placeholder="Поиск">
                    </p>
                </td>
            </tr>
        </table>

        <div class="documents_container">
            {foreach $objects->getItems() as $object}
            <div class="documents_item" objid="{$object->getId()}" type="{if $object->isFolder()}folder{else}file{/if}">
                <div class="item_icon">
                    {if $object->isFolder()}
                        <a href="index.php?action=index&parent={$object->getId()}">
                            <img src="{$web_root}images/{$icon_theme}/64x64/actions/stock_bookmark.png">
                        </a>
                    {elseif ($object->isFileExists())}
                        <a href="{$object->getFileLink()}" target="_blank">
                            <img src="{$object->getIconLink()}">
                        </a>
                    {else}
                        <img src="{$web_root}images/{$icon_theme}/64x64/actions/list-remove.png">
                    {/if}
                </div>
                <div class="item_title">
                    {if $object->isFolder()}
                        <a href="index.php?action=index&parent={$object->getId()}">
                            {$object->title}
                        </a>
                    {elseif ($object->isFileExists())}
                        <a href="{$object->getFileLink()}" target="_blank">
                            {$object->browserFile}
                        </a>
                    {else}
                        {$object->browserFile}
                    {/if}
                </div>
            </div>
            {/foreach}
        </div>
    {/if}

    {if $canEdit}
        <script>
            jQuery(document).ready(function(){
                // добавляем выпадающие кнопки редактирования документа
                jQuery(".documents_item[type=file]").prepend('<div class="item_manage">' +
                        '<i class="icon-pencil documents_item_manage" style="cursor: pointer;"></i>' +
                        '</div>');
             	// добавляем выпадающие кнопки редактирования папки
                jQuery(".documents_item[type=folder]").prepend('<div class="item_manage">' +
                        '<i class="icon-pencil documents_item_manage" style="cursor: pointer;"></i>' +
                        '</div>');

                // управляшки
                jQuery(".documents_item_manage").popover({
                    html: true,
                    placement: "left",
                    title: "Управление",
                    content: function(){
                        var parent = jQuery(this).parents(".documents_item").first();
                        var links = new Array();
                        if (jQuery(parent).attr("type") == "file") {
                            links[links.length] = '<li><a href="files.php?action=delete&id=' + jQuery(parent).attr("objid") + '">Удалить</a></li>';
                            links[links.length] = '<li><a href="files.php?action=edit&id=' + jQuery(parent).attr("objid") + '">Редактировать</a></li>';
                        }
                        if (jQuery(parent).attr("type") == "folder") {
                            links[links.length] = '<li><a href="index.php?action=delete&id=' + jQuery(parent).attr("objid") + '">Удалить</a></li>';
                            links[links.length] = '<li><a href="index.php?action=edit&id=' + jQuery(parent).attr("objid") + '">Редактировать</a></li>';
                        }
                        return '<ul class="nav nav-pills nav-stacked">' + links.join("") + "</ul>";
                    }
                });
            });
        </script>
    {/if}
    <script>
        jQuery(document).ready(function(){
            var defaultValue = jQuery(".documents_container").first().html();
            var placeholder = jQuery(".documents_container").first();
            jQuery("#search").on("keyup", function(){
                var value = jQuery(this).val();
                if (value.length < 3) {
                    // если текст слишком короткий, то показывае исходный
                    jQuery(placeholder).html(defaultValue);
                    return false;
                }
                // показываем заглушку
                jQuery(placeholder).html('<div style="width: 100%; height: 200px; opacity: 0.5; background-repeat: no-repeat; background-position: center center; background-image: url({$web_root}images/loader.gif);"></div>');
                jQuery.ajax({
                    url: "#",
                    type: "GET",
                    cache: false,
                    dataType: "json",
                    data: {
                        action: "search",
                        query: value
                    },
                    success: function(data){
                        if (data.length == 0) {
                            jQuery(placeholder).html("По Вашему запросу ничего не найдено");
                            return true;
                        }
                        jQuery(placeholder).empty();
                        jQuery.each(data, function(index, object){
                            if (object.type == "folder") {
                                var str = '<div class="documents_item" objid="' + object.id + '" type="folder">';
                                str += '<div class="item_icon">';
                                str += '<a href="index.php?action=index&parent=' + object.id + '">';
                                str += '<img src="{$web_root}images/{$icon_theme}/64x64/actions/stock_bookmark.png">';
                                str += '</a>';
                                str += '</div>';
                                str += '<div class="item_title">';
                                str += '<a href="index.php?action=index&parent=' + object.id + '">';
                                str += object.title;
                                str += '</a>';
                                str += '</div>';
                                str += '</div>';
                            } else if (object.type == "file") {
                                var str = '<div class="documents_item" objid="' + object.id + '" type="file">';
                                str += '<div class="item_icon">';
                                str += '<a href="' + object.link + '">';
                                str += '<img src="' + object.icon + '">';
                                str += '</a>';
                                str += '</div>';
                                str += '<div class="item_title">';
                                str += '<a href="' + object.link + '">';
                                str += object.title;
                                str += '</a>';
                                str += '</div>';
                                str += '</div>';
                            }
                            jQuery(placeholder).append(str);
                        });
                    }
                });
            });
        });
    </script>
{/block}

{block name="asu_right"}
    {include file="_documents/_folder/common.right.tpl"}
{/block}
