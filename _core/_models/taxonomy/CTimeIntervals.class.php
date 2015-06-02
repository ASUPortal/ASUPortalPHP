<?php

class CTimeIntervals extends CTerm {
	protected $_table = TABLE_YEARS;

	public function attributeLabels() {
		return array(
				"name" => "Имя полное",
				"year.date_start" => "Дата начала\окончания года",
				"date_start" => "Дата начала года",
				"date_end" => "Дата окончания года",
				"comment" => "Комментарий"
		);
	}
	public function validationRules() {
		return array(
				"required" => array(
					"name",
					"date_start",
					"date_end",
				)
		);
	}
	public function fieldsProperty() {
		return array(
				"date_start" => array(
						"type" => FIELD_MYSQL_DATE,
						"format" => "d.m.Y"
				),
				"date_end" => array(
						"type" => FIELD_MYSQL_DATE,
						"format" => "d.m.Y"
				)
		);
	}
}
