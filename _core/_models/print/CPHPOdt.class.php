<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 18:34
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class CPHPOdt
 * @deprecated
 */
class CPHPOdt extends CAbstractDocumentWriter {
    public function loadTemplate($file) {
        if (file_exists($file)) {
            $template = new CPHPOdt_template($file);
            return $template;
        } else {
            trigger_error("Файл ".$file." не найден");
        }
    }
}
