<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 05.05.12
 * Time: 16:44
 * To change this template use File | Settings | File Templates.
 */
class CUnorderedListWidget extends CWidget implements IDisplayable {
    /**
     * Отображение неупорядоченного списка
     *
     * Принимает массив следующего формата
     * array(
     *      'items' => CArrayList,
     *      'itemTemplate' => '_item.html.php',
     *      'id' => 'list_id',
     *      'class' => 'list_class'
     * )
     *
     * @static
     * @param array $arr
     */
    public static function display(array $arr = null) {
        $items = $arr['items'];
        $id = null;
        $class = null;
        $style = null;

        if (array_key_exists("id", $arr)) {
            $id = ' id="'.$arr['id'].'"';
        }
        if (array_key_exists("class", $arr)) {
            $class = ' class="'.$arr['class'].'"';
        }
        if (array_key_exists("style", $arr)) {
            $style = ' style="'.$arr['style'].'"';
        }

        echo '<ul'.$id.$class.$style.'>';
        foreach ($items->getItems() as $item) {
            require(TEMPLATES_DIR.$arr['itemTemplate']);
        }
        echo '</ul>';
    }
}
