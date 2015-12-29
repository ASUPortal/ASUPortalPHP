<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 10:53
 * To change this template use File | Settings | File Templates.
 */
class CDiscipline extends CActiveModel {
	protected $_table = TABLE_DISCIPLINES;
	protected $_books = null;
    private $_questions = null;
    
    protected function relations() {
    	return array(
    		"books" => array(
    			"relationPower" => RELATION_MANY_TO_MANY,
    			"storageProperty" => "_books",
    			"joinTable" => TABLE_DISCIPLINES_BOOKS,
    			"leftCondition" => "subject_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
    			"rightKey" => "book_id",
    			"managerClass" => "CBaseManager",
    			"managerGetObject" => "getCorriculumBook"
    		)
    	);
    }
    
    public function attributeLabels() {
    	return array(
    		"name" => "Название",
    		"library_code" => "Код из библиотеки"
    	);
    }
    
    /**
     * Вопросы, которые по данной дисциплине есть
     *
     * @return CArrayList
     */
    public function getQuestions() {
        if (is_null($this->_questions)) {
            $this->_questions = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SEB_QUESTIONS, "discipline_id=".$this->getId())->getItems() as $ar) {
                $q = new CSEBQuestion($ar);
                $this->_questions->add($q->getId(), $q);
            }
        }
        return $this->_questions;
    }
    
    /**
     * Добавление литературы с сайта библиотеки
     */
    public static function actionAddFromUrl($codeDiscipl, $subject_id) {
    	// подключаем PHP Simple HTML DOM Parser
    	require_once(CORE_CWD."/_core/_external/smarty/vendor/simple_html_dom.php");
    
    	$num = 1;
    	do {
    		// подключаем библиотеку curl с указанием proxy
    		$proxy = CSettingsManager::getSettingValue("proxy_address");
    		$curl = curl_init();
    		curl_setopt($curl, CURLOPT_PROXY, $proxy);
    		 
    		// ссылка для загрузки изданий из библиотеки
    		$link = CSettingsManager::getSettingValue("link_library");
    
    		curl_setopt($curl, CURLOPT_URL, $link.$codeDiscipl);
    		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    		$str = curl_exec($curl);
    		curl_close($curl);
    		 
    		// создаём DOM объект из строки
    		$html = str_get_html($str);
    		 
    		$num++;
    		sleep(2);
    		if(empty($html)) {
    			return "URL ".$link.$codeDiscipl." не доступен, проверьте адрес прокси в настройках портала";
    		}
    	} while (count($html->find('#PanelWait')) != 0 and $num <= 5);
    
    	if(!empty($html) and count($html->find('#PanelWait')) == 0) {
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
    				$disciplineBook = new CCorriculumDisciplineBook();
    				$disciplineBook->book_id = $library->getId();
    				$disciplineBook->subject_id = $subject_id;
    				$disciplineBook->save();
    			} else {
    				foreach ($corriculumBooks->getItems() as $ar) {
    					$query = new CQuery();
    					$query->select("disc_books.*")
	    					->from(TABLE_DISCIPLINES_BOOKS." as disc_books")
	    					->condition("disc_books.book_id = '.$ar->id.' and disc_books.subject_id != ".$subject_id);
    					if ($query->execute()->getCount() > 0) {
    						$disciplineBook = new CCorriculumDisciplineBook();
    						$disciplineBook->book_id = $ar->id;
    						$disciplineBook->subject_id = $subject_id;
    						$disciplineBook->save();
    					}
    				}
    			}
    		}
    		return "Данные добавлены успешно";
    		// очищаем память
    		$html->clear();
    		unset($html);
    	} elseif(count($html->find('#PanelWait'))) {
    		return "Превышено время ожидания формирования отчёта";
    	} else {
    		return "URL ".$link.$codeDiscipl." не доступен, проверьте адрес прокси в настройках портала";
    	}
    }
}
