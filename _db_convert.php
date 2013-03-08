<?php

$time = microtime(true);

$db = 'asu_demo';
$login = 'root';
$passw = 'root';
$host = 'localhost';

$res = mysql_connect($host, $login, $passw);
mysql_select_db($db);

mysql_query('SET NAMES utf8;');

$rs = mysql_query('SHOW TABLES;');
print mysql_error(); //the notorious 'command out of synch' message :(
while (($row=mysql_fetch_assoc($rs))!==false) {

$time1 = microtime(true);
//print $row['Tables_in_vspomni2']."\n";
$table_name = $row['Tables_in_'.$db];
$query = 'SHOW CREATE TABLE '.$table_name;

$row_create = mysql_query($query);
print mysql_error();
$row1 = mysql_fetch_assoc($row_create);

if (strpos($row1['Create Table'], 'DEFAULT CHARSET=utf8') !== false)
{
print 'Table '.$table_name.' — skipped'."\n";
continue;
}

$create_table_scheme = str_ireplace('cp1251', 'utf8', $row1['Create Table']); // CREATE TABLE SCHEME
$create_table_scheme = str_ireplace('ENGINE=InnoDB', 'MyISAM', $create_table_scheme);
$create_table_scheme .= ' COLLATE utf8_bin';

//print $create_table_scheme;
//continue;

$query = 'RENAME TABLE '.$table_name.' TO '.$table_name.'_tmp_export'; // RENAME TABLE;
mysql_query($query);
$error = mysql_error();
if (strlen($error) > 0)
{
print $error.' — LINE '.__LINE__."\n";
break;
}

$query = $create_table_scheme;
mysql_query($query);
$error = mysql_error();
if (strlen($error) > 0)
{
print $error.' — LINE '.__LINE__."\n";
break;
}

$query = 'ALTER TABLE '.$table_name.' DISABLE KEYS';
mysql_query($query);
$error = mysql_error();
if (strlen($error) > 0)
{
print $error.' — LINE '.__LINE__."\n";
break;
}

$query = 'INSERT INTO '.$table_name.' SELECT * FROM '.$table_name.'_tmp_export';
mysql_query($query);
$error = mysql_error();
if (strlen($error) > 0)
{
print $error.' — LINE '.__LINE__."\n";
break;
}

$query = 'DROP TABLE '.$table_name.'_tmp_export';
mysql_query($query);
$error = mysql_error();
if (strlen($error) > 0)
{
print $error.' — LINE '.__LINE__."\n";
break;
}

$time3 = microtime(true);
$query = 'ALTER TABLE '.$table_name.' ENABLE KEYS';
mysql_query($query);
$error = mysql_error();
if (strlen($error) > 0)
{
print $error.' — LINE '.__LINE__."\n";
break;
}

print 'Enable keys to '.$table_name.'. time -'.(microtime(true) - $time3)."\n";
print 'converted '.$table_name.'. time — '.(microtime(true) - $time1)."\n\n";

}
mysql_free_result($rs);

print 'done. total time -'.(microtime(true) - $time);
?>