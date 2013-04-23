<?php
	require_once("core.php");
	$value = "";
	if (file_exists(CORE_CWD."/_core/_cache/numerator.txt")) {
		$fHandler = fopen(CORE_CWD."/_core/_cache/numerator.txt", "r");
		/**
		 * Проверяем, что файл создан не позднее последних 15 минут
		 */
		$created = filectime(CORE_CWD."/_core/_cache/numerator.txt");
		$nowTime = time();
		if (abs($nowTime - $created) > (15 * 60)) {
			$fHandler = fopen(CORE_CWD."/_core/_cache/numerator.txt", "w+");
			fputs($fHandler, "1");			
		}
	} else {
		$fHandler = fopen(CORE_CWD."/_core/_cache/numerator.txt", "w+");
		fputs($fHandler, "1");
	}
	/**
	 * Ставим указатель в начало файла
	 */
	$fHandler = fopen(CORE_CWD."/_core/_cache/numerator.txt", "r");
	$value = fgets($fHandler);
	/**
	 * Ставим в файл следующее значение
	 */
	$fHandler = fopen(CORE_CWD."/_core/_cache/numerator.txt", "w+");
	fputs($fHandler, ($value + 1));
	var_dump($value);
	
	