<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.03.13
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplinesController extends CFlowController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();
    }
    public function actionEdit() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $corriculum = CCorriculumsManager::getCorriculum($discipline->cycle->corriculum->getId());
        $this->addActionsMenuItem(array(
        	"title" => "Удалить выделенные компетенции",
        	"icon" => "actions/edit-delete.png",
        	"form" => "#MainView",
        	"link" => "competentions.php",
        	"action" => "delete"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Удалить выделенные учебники",
        	"icon" => "actions/edit-delete.png",
        	"form" => "#Books",
        	"link" => "books.php?discipline_id=".CRequest::getInt("id"),
        	"action" => "delete"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Печать по шаблону",
        	"link" => "#",
        	"icon" => "devices/printer.png",
        	"template" => "formset_corriculum_disciplines"
        ));
        /**
         * Подключаем скрипты для няшности
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("cycle", $discipline->cycle);
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/edit.tpl");
    }
    public function actionAdd() {
        $discipline = new CCorriculumDiscipline();
        $discipline->cycle_id = CRequest::getInt("id");
        $this->setData("cycle", CCorriculumsManager::getCycle(CRequest::getInt("id")));
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionSave() {
        $discipline = new CCorriculumDiscipline();
        $discipline->setAttributes(CRequest::getArray($discipline::getClassName()));
        if ($discipline->validate()) {
            $discipline->save();
            if ($this->continueEdit()) {
                $this->redirect("disciplines.php?action=edit&id=".$discipline->getId());
            } else {
                $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
            }
            return true;
        }
        $this->setData("cycle", $discipline->cycle);
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplines/add.tpl");
    }
    public function actionDel() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $id = $discipline->cycle_id;
        $discipline->remove();
        $this->redirect("cycles.php?action=edit&id=".$id);
    }
    public function actionUp() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        /**
         * Проверим, вдруг это первый запуск и ничего не отсортировано еще
         */
        if (is_null($discipline->ordering)) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $i = 1;
                foreach ($cycle->disciplines->getItems() as $d) {
                    $d->ordering = $i;
                    $d->save();
                    $i++;
                }
            }
        }
        /**
         * Двигаем только если текущая не первая
         */
        if ($discipline->ordering > 1) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $d = $cycle->getNthDiscipline(($discipline->ordering - 1));
                if (!is_null($d)) {
                    $curr = $discipline->ordering;
                    $d->ordering = $curr;
                    $discipline->ordering = ($curr - 1);
                    $discipline->save();
                    $d->save();
                }
            }
        }
        /**
         * Возвращаем обратно
         */
        $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
    }
    public function actionDown() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        /**
         * Проверим, вдруг это первый запуск и ничего не отсортировано еще
         */
        if (is_null($discipline->ordering)) {
            $cycle = $discipline->cycle;
            if (!is_null($cycle)) {
                $i = 1;
                foreach ($cycle->disciplines->getItems() as $d) {
                    $d->ordering = $i;
                    $d->save();
                    $i++;
                }
            }
        }
        /**
         * Двигаем только если текущая не последняя
         */
        $cycle = $discipline->cycle;
        if (!is_null($cycle)) {
            if ($discipline->ordering < $cycle->disciplines->getCount()) {
                $curr = $discipline->ordering;
                $d = $cycle->getNthDiscipline($curr + 1);
                if (!is_null($d)) {
                    $d->ordering = $curr;
                    $discipline->ordering = ($curr + 1);
                    $discipline->save();
                    $d->save();
                }
            }
        }
        /**
         * Возвращаем обратно
         */
        $this->redirect("cycles.php?action=edit&id=".$discipline->cycle_id);
    }
    /**
     * Добавление литературы с сайта библиотеки
     */
    public function actionAddFromUrl() {
    	// подключаем PHP Simple HTML DOM Parser
    	require_once(CORE_CWD."/_core/_external/smarty/vendor/simple_html_dom.php");
    	 
    	$num = 1;
    	do {
    		// подключаем библиотеку curl с указанием proxy
    		$proxy = CSettingsManager::getSettingValue("proxy_address");
    		$curl = curl_init();
    		//curl_setopt($curl, CURLOPT_PROXY, $proxy);
    		 
    		// ссылка для загрузки изданий из библиотеки
    		$link = CSettingsManager::getSettingValue("link_library");
    		 
    		// код дисциплины
    		$discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
    		$codeDiscipl = $discipline->codeFromLibrary;
    
    		curl_setopt($curl, CURLOPT_URL, $link);
    		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    		$str = curl_exec($curl);
    		curl_close($curl);
    		 
    		// создаём DOM объект из строки
    		$html = str_get_html($str);
    		 
    		$num++;
    		sleep(2);
    		if(empty($html)) {
    			$this->setData("message", "URL ".$link.$codeDiscipl." не доступен, проверьте адрес прокси в настройках портала");
    			$this->renderView("_flow/dialog.ok.tpl", "", "");
    		}
    	} while (count($html->find('#PanelWait')) != 0 and $num <= 5);
    	 
    	if(count($html->find('#PanelWait'))) {
    		$this->setData("message", "Превышено время ожидания формирования отчёта");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    	}
    	 
    	if(!empty($html)) {
    		$result = array();
    
    		// массив всех элементов
    		$result1 = array();
    		$arr1 = array();
    		if(count($html->find(CSettingsManager::getSettingValue("index_kko_all")))) {
    			foreach($html->find(CSettingsManager::getSettingValue("index_kko_all")) as $k=>$tr) {
    				foreach ($tr->find(CSettingsManager::getSettingValue("izdan_names")) as $kk=>$td) {
    					$arr1[$k][$kk] = $td->plaintext;
    				}
    				$result1[] = $arr1[$k][1];
    			}
    		}
    
    		// массив элементов с низким, либо нулевым ККО
    		$result2 = array();
    		$arr2 = array();
    		if(count($html->find(CSettingsManager::getSettingValue("index_kko_extraLow")))) {
    			foreach($html->find(CSettingsManager::getSettingValue("index_kko_extraLow")) as $k=>$tr) {
    				foreach ($tr->find(CSettingsManager::getSettingValue("izdan_names")) as $kk=>$td) {
    					$arr2[$k][$kk] = $td->plaintext;
    				}
    				$result2[] = $arr2[$k][1];
    			}
    		}
    
    		// исключаем из первого массива элементы второго
    		$result = array_unique(array_diff($result1, $result2));
    
    		foreach ($result as $literature) {
    			$set = new CRecordSet();
    			$queryLibrary = new CQuery();
    			$set->setQuery($queryLibrary);
    			$queryLibrary->select("books.*")
	    			->from(TABLE_CORRICULUM_BOOKS." as books")
	    			->condition("books.book_name = '".$literature."'");
    			$corriculumBooks = new CArrayList();
    			foreach ($set->getItems() as $ar) {
    				$item = new CCorriculumBook($ar);
    				$corriculumBooks->add($item->getId(), $item);
    			}
    			if ($corriculumBooks->getCount() == 0) {
    				$library = new CCorriculumBook();
    				$library->book_name = $literature;
    				$library->save();
    				$disciplineLibrary = new CCorriculumDisciplineBook();
    				$disciplineLibrary->book_id = $library->getId();
    				$disciplineLibrary->discipline_id = $codeDiscipl;
    				$disciplineLibrary->save();
    			} else {
    				foreach ($corriculumBooks->getItems() as $ar) {
    					$query = new CQuery();
    					$query->select("disc_books.*")
	    					->from(TABLE_CORRICULUM_DISCIPLINE_BOOKS." as disc_books")
	    					->condition("disc_books.book_id = '.$ar->id.' and discipline_id != ".$codeDiscipl);
    					if ($query->execute()->getCount() > 0) {
    						$disciplineLibrary = new CCorriculumDisciplineBook();
    						$disciplineLibrary->book_id = $ar->id;
    						$disciplineLibrary->discipline_id = $codeDiscipl;
    						$disciplineLibrary->save();
    					}
    				}
    			}
    		}
    
    		$this->setData("message", "Данные добавлены успешно");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    
    		// очищаем память
    		$html->clear();
    		unset($html);
    	} else {
    		$this->setData("message", "URL ".$link.$codeDiscipl." не доступен, проверьте адрес прокси в настройках портала");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    	}
    }
}
