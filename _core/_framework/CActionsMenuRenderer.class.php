<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 26.01.14
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */

class CActionsMenuRenderer {
    private static $childContainers = 0;
    /**
     * Отображение меню справа на сайте
     *
     * @param array $items
     */
    public function render(array $items) {
        foreach ($items as $item) {
            $this->renderItem($item);
            if (array_key_exists("child", $item)) {
                $this->renderChilds($item["child"]);
            }
        }
        ?>
        <script>
            jQuery(document).ready(function(){
                jQuery(".popoverable").popover({
                    placement: "left",
                    html: true,
                    title: function(){
                        return jQuery(this).attr("asu-title");
                    },
                    content: function(){
                        var container = "#menu_children_container_" + jQuery(this).attr("asu-popover");
                        return jQuery(container).html();
                    }
                });
            });
        </script>
    <?php
    }

    /**
     * Отображение всех дочерних пунктов меню
     *
     * @param array $items
     */
    private function renderChilds(array $items) {
        echo '<div class="menu_children_container" id="menu_children_container_'.self::$childContainers.'">';
        foreach ($items as $item) {
            $this->renderChildItem($item);
        }
        echo '</div>';
        self::$childContainers++;
    }

    /**
     * Отображение дочернего пункта меню
     *
     * @param array $item
     */
    private function renderChildItem(array $item) {
        echo '<div class="menu_child_container">';
        echo '<a href="'.$item["link"].'">';
        echo '<img src="'.WEB_ROOT.'images/'.ICON_THEME.'/32x32/'.$item['icon'].'">';
        echo $item['title'];
        echo '</a>';
        echo '</div>';
    }
    /**
     * Отрисовать печать по шаблону
     *
     * @param array $item
     */
    private function renderPrintMenuItem(array $item) {
        // кнопка печати по шаблону
        echo '<div class="menu_item_container">';
        echo '<a href="#print_'.self::$childContainers.'" data-toggle="modal">';
        echo '<img src="'.WEB_ROOT.'images/'.ICON_THEME.'/32x32/'.$item['icon'].'">';
        echo $item['title'];
        echo '</a>';
        echo '</div>';
        // диалог с выбором шаблонов
        echo '<div id="print_'.self::$childContainers.'" class="modal hide fade">';
        echo '<div class="modal-header">';
        echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
        echo '<h3 id="myModalLabel">'.$item["title"].'</h3>';
        echo '</div>';
        echo '<div class="modal-body">';
        CHtml::printOnTemplate($item["template"]);
        echo '</div>';
        echo '</div>';
        self::$childContainers++;
    }

    /**
     * Отображение одного пункта меню
     *
     * @param array $item
     */
    private function renderItem(array $item) {
        if (array_key_exists("template", $item)) {
            $this->renderPrintMenuItem($item);
        } else {
            if (array_key_exists("child", $item)) {
                echo '<div class="menu_item_container popoverable" asu-popover="'.self::$childContainers.'" asu-title="'.$item['title'].'">';
            } else {
                echo '<div class="menu_item_container">';
            }
            echo '<a href="'.$item["link"].'">';
            echo '<img src="'.WEB_ROOT.'images/'.ICON_THEME.'/32x32/'.$item['icon'].'">';
            echo $item['title'];
            echo '</a>';
            echo '</div>';
        }
    }
}