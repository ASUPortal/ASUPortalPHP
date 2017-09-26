<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 20:28
 * To change this template use File | Settings | File Templates.
 */
class CPrintController extends CFlowController {
    private $_isDebug = false;
    private $printService = null;

    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление печатью по шаблону");

        parent::__construct();
    }
    public function actionIndex() {
        $this->renderView("_print/index.tpl");
    }

    /**
     * Получаем экземпляр сервиса печати
     *
     * @return CPrintService
     */
    private function getPrintService() {
        if (is_null($this->printService)) {
            $this->printService = new CPrintService();
        }
        return $this->printService;
    }

    public function actionPrint() {
    	try {
	        if (CRequest::getString("id") == "") {
	            throw new Exception("Не выбраны объекты для печати!");
	        }
	        /**
	    	 * Получаем обратно параметры контекста из запроса.
	    	 * Мы получаем:
	    	 * 1. Класс менеджера
	    	 * 2. Метод менеджера для получения нужного объекта
	    	 * 3. Идентификатор объекта
	    	 * 4. Идентификатор печатной формы
	    	 */
	    	$managerClass = CRequest::getString("manager");
	    	$managerMethod = CRequest::getString("method");
	    	$objectId = CRequest::getString("id");
	    	$ids = explode(":", CRequest::getString("id"));
	    	foreach ($ids as $id) {
	    		$objectId = $id;
	    	}
	    	$formId = CRequest::getInt("template");
	    	/**
	    	 * Получаем объект через менеджер
	    	 * (если объект является экземпляром CArrayList, для совместимости получим первое значение экземпляра CModel из CArrayList)
	    	 */
	    	if (!$managerClass::$managerMethod($objectId) instanceof CArrayList) {
	    		$object = $managerClass::$managerMethod($objectId);
	    	} else {
	    		$object = $managerClass::$managerMethod($objectId)->getFirstItem();
	    	}
	    	if (is_null($object)) {
	    		throw new Exception("Объекта для печати с указанным ID нет!");
	    	}
	    	$form = CPrintManager::getForm($formId);
	    	if (is_null($form)) {
	    		throw new Exception("Указанного шаблона для печати нет в базе!");
	    	}
	        /**
	         * Печатаем
	         */
	    	if ($object instanceof CModel) {
	    		$filename = $this->getPrintService()->printTemplate($form, $object);
	    	} else {
	    		throw new Exception("Объект для печати должен быть экземпляром класса CModel!");
	    	}
			/**
			 * Отдаем документ пользователю
			 * Не отдаем, если у нас тут групповая печать
			 */
			if (CRequest::getInt("noredirect") == "1") {
				echo json_encode(array(
					"filename" => PRINT_DOCUMENTS_DIR.$filename,
					"url" => PRINT_DOCUMENTS_DIR.$filename
				));
			} else {
				$this->redirect(PRINT_DOCUMENTS_URL.$filename);
			}
		} catch (Exception $e) {
			/**
			 * Показываем исключения, возникшие в процессе печати
			 */
			echo $e->getMessage();
		}
    }
    public function actionShowForms() {
        $formsetName = CRequest::getString("template");
        $formset = CPrintManager::getFormset($formsetName);
        if (!is_null($formset)) {
            $forms = new CArrayList();
            foreach ($formset->forms->getItems() as $form) {
                $forms->add($form->getId(), $form->title);
            }

            $this->showPickList($forms, get_class($this), "PrePrintWithTemplate");
        }
    }
    public function actionPrePrintWithTemplate() {
        if ($this->getSelectedInPickListDialog()->getCount() == 0) {
            return true;
        }
        $selectedForm = CPrintManager::getForm($this->getSelectedInPickListDialog()->getFirstItem());
        if (!is_null($selectedForm)) {
            /**
             * Если это форма без диалога параметров, то просто перекинем
             * пользователя на страницу генерации документа
             */
            if ($selectedForm->properties_show_dialog != "1") {
                $url = WEB_ROOT."_modules/_print/?action=print".
                        "&template=".$selectedForm->getId();
                foreach (self::getStatefullBean()->getItems() as $key=>$value) {
                    $url .= "&".$key."=".$value;
                }
                // для совместимости передадим в качестве параметра "id" первое значение из массива "selectedInView"
                if (self::getStatefullBean()->getItem("id") instanceof CArrayList) {
                    $url .= "&id=".self::getStatefullBean()->getItem("id")->getFirstItem();
                }
                $this->redirect($url);
            } else {
                // тут с диалогом, передадим ему управление
                self::getStatefullBean()->add("template", $selectedForm->getId());
                $this->redirectNextAction($selectedForm->properties_controller, $selectedForm->properties_method);
            }
        }
    }
    public function actionPrintWithBeanData() {
        $url = WEB_ROOT."_modules/_print/?action=print";
        foreach (self::getStatefullBean()->getItems() as $key=>$value) {
            if (!is_object($value)) {
                $url .= "&".$key."=".$value;
            }
        }
        $url .= "&beanId=".self::getStatefullBean()->getBeanId();
        // для совместимости передадим в качестве параметра "id" первое значение из массива "selectedInView"
        if (self::getStatefullBean()->getItem("id") instanceof CArrayList) {
            $url .= "&id=".self::getStatefullBean()->getItem("id")->getFirstItem();
        }
        $this->redirect($url);
    }
}
