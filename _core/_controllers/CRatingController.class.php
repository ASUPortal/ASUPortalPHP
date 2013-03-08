<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 20.08.12
 * Time: 14:05
 * To change this template use File | Settings | File Templates.
 */
class CRatingController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление доступом пользователей");

        parent::__construct();
    }
    public function actionIndex() {
        $this->addJSInclude("_core/HighCharts/highcharts.src.js");
        $this->addJSInclude("_core/HighCharts/modules/exporting.src.js");
        $this->addJSInclude("_modules/_rating/rating.js");
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addJSInclude("_core/jTagIt/tag-it.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addCSSInclude("_core/jTagIt/jquery.tagit.css");
        $this->renderView("_rating/index.tpl");
    }
}
