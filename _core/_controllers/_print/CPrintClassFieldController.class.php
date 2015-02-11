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
	private function lookupFolder($folder) {
		$folderHandle = opendir($folder);
		while (false !== ($file = readdir($folderHandle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($folder.CORE_DS.$file)) {
					$this->lookupFolder($folder.CORE_DS.$file);
				} else {
					if (mb_strpos($file, ".class.php") !== false) {
						if (!class_exists($file, false)) {
							require_once($folder.CORE_DS.$file);
						}
						$model = substr($file, 0, strpos($file, "."));
						if (is_a($model, "IPrintClassField", true)) {
							if (!interface_exists($model, false)) {
								$reflection = new ReflectionClass($model);
								if ($reflection->isInstantiable()) {
									$object = new $model();
									$this->models->add($this->models->getCount(), $object);
								}
							}
						}
					}
				}
			}
		}
	}
	private function getModelsList() {
		/**
		 * Берем папку моделей и ищем все подпапки
		 */
		$modelsDir = CORE_CWD.CORE_DS."_core".CORE_DS."_models";
		$this->models = new CArrayList();
		$this->lookupFolder($modelsDir);
		return $this->models;
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