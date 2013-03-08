<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 05.05.12
 * Time: 16:26
 * To change this template use File | Settings | File Templates.
 */
class COrgStructureWidget extends CWidget implements IDisplayable{
    /**
     * Отображение организационной структуры.
     *
     * Параметры вызова:
     * array(
     *      'items' => CArrayList,
     *      'itemTemplate' => '_item.html.php'
     * )
     *
     * CArrayList должен содержать объекты типа CPerson
     *
     * @static
     * @param array $arr
     */
    public static function display(array $arr = null) {
        $r = new CArrayList();
        foreach ($arr['items']->getItems() as $i) {
            if ($i->getSubordinators()->getCount() > 0 && $i->getManagerId() == 0) {
                $r->add($r->getCount(), $i);
            }
        }

        CUnorderedListWidget::display(array(
            'items' => $r,
            'itemTemplate' => '_orgStructureItem.html.php',
            'id' => $arr['id'],
            'style' => $arr['style']
        ));
    }
}
