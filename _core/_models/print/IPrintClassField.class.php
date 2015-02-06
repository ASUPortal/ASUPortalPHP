<?php
interface IPrintClassField {
	public function getFieldName();
	public function getFieldDescription();
	public function getParentClassField();
	public function getFieldType();
	public function execute($contextObject);
	public function getFilePath();
	
	const FIELD_TABLE = "table";
	const FIELD_TEXT = "text";
}