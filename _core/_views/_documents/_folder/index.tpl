{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>{$title}</h2>

    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
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
                        <a href="{$web_root}library/gost/{$object->nameFile}" target="_blank">
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
                        return '<ul class="nav nav-pills nav-stacked">' + links.join("") + "</ul>";
                    }
                });
            });
        </script>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_documents/_folder/common.right.tpl"}
{/block}