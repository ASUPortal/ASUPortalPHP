<?php
class CPrintClassFieldController extends CBaseController {
	/**
	 * @var $models CArrayList
	 */
	private $models = null;

	public function __construct() {
		if (!CSession::isAuth()) {
			$this->redirectNoAccess();
		}
	
		$this->_smartyEnabled = true;
		$this->setPageTitle("Класс-описатели полей");
	
		parent::__construct();
	}
	private function getModelsList() {
		return CUtils::getAllClassesWithInterface("IPrintClassField");
	}
	public function actionIndex() {
		$classes = $this->getModelsList();
		/**
		 * Формируем меню
		 */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
		$this->setData("classes", $classes);
		$this->renderView("_print/classfield/index.tpl");
	}
}