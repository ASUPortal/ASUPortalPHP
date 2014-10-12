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
        $this->renderItemContent($item);
        echo '</div>';
    }
    /**
     * Отрисовать печать по шаблону
     *
     * @param array $item
     */
    private function renderMenuItemPrint(array $item) {
        // кнопка печати по шаблону
        echo '<div class="menu_item_container">';
        echo '<a href="'.WEB_ROOT.'_modules/_print/?action=ShowForms&template='.$item['template'].'" asu-action="flow">';
        $formset = CPrintManager::getFormset($item["template"]);
        if (!is_null($formset)) {
            $var = $formset->computeTemplateVariables();
            foreach ($var as $key=>$value) {
                echo '<div asu-type="flow-property" name="'.$key.'" value="'.$value.'"></div>';
            }
        }
        echo '<img src="'.WEB_ROOT.'images/'.ICON_THEME.'/32x32/'.$item['icon'].'">';
        echo $item['title'];
        echo '</a>';
        echo '</div>';
        self::$childContainers++;
    }
    private function renderMenuItemDefault(array $item) {
        echo '<a href="'.$item["link"].'">';
        echo '<img src="'.WEB_ROOT.'images/'.ICON_THEME.'/32x32/'.$item['icon'].'">';
        echo $item['title'];
        echo '</a>';
    }
    private function renderMenuItemAjaxAction(array $item) {
        echo '<a href="#" id="ajaxMenuAction_'.self::$childContainers.'">';
        echo '<img src="'.WEB_ROOT.'images/'.ICON_THEME.'/32x32/'.$item['icon'].'">';
        echo $item['title'];
        echo '</a>';

        ?>
        <script>
            jQuery(document).ready(function(){
                jQuery("#ajaxMenuAction_<?php echo self::$childContainers; ?>").on("click", function(){
                    var form = jQuery("<?php echo $item["form"]; ?>");
                    jQuery(form).attr("action", "<?php echo $item['link']; ?>");
                    var action = jQuery("[name=action]", form);
                    if (action.length == 0) {
                        var input = jQuery("<input />", {
                            type: "hidden",
                            name: "action",
                            value: "<?php echo $item["action"]; ?>"
                        });
                        jQuery(form).append(input);
                    }
                    jQuery(form).submit();
                    return false;
                });
            });
        </script>
        <?php
        self::$childContainers++;
    }
    private function renderItemContent(array $item) {
        if (array_key_exists("template", $item)) {
            $this->renderMenuItemPrint($item);
        } elseif (array_key_exists("form", $item)) {
            $this->renderMenuItemAjaxAction($item);
        } else {
            $this->renderMenuItemDefault($item);
        }
    }

    /**
     * Отображение одного пункта меню
     *
     * @param array $item
     */
    private function renderItem(array $item) {
        if (array_key_exists("child", $item)) {
            echo '<div class="menu_item_container popoverable" asu-popover="'.self::$childContainers.'" asu-title="'.$item['title'].'">';
        } else {
            echo '<div class="menu_item_container">';
        }
        $this->renderItemContent($item);
        echo '</div>';
    }
}