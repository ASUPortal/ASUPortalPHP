{header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0')}
{header('Pragma: no-cache')}
{header('Content-Type: application/x-msexcel; charset=utf-8; format=attachment;')}
{header('Content-Disposition: attachment; filename=raspisanie.xls')}

<h2>Расписание занятий, {$yearPart->getValue()} семестр {$year->getValue()} года{if (!is_null($name))}, {$name->getName()}{/if}</h2>

{include file="__public/_schedule/view.form.tpl"}