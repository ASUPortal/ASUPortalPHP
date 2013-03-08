<?php $paginator->options(array('url' => $this->passedArgs)); ?>

<table>
<tr>
	<?php foreach ($forms as $form) {?>
		<td><?php echo $html->link($form['Form']['title'], '/corriculums/view/'.$speciality.'/'.$form['Form']['id']); ?></td>
	<?php } ?>
</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<th colspan="3"><?php echo $paginator->sort('Р”РёСЃС†РёРїР»РёРЅС‹', 'Discipline.title'); ?></th>
	<th colspan="6">Р Р°Р·РґРµР»РµРЅРёРµ РѕР±СЉРµРјР° СѓС‡РµР±РЅРѕРіРѕ РїР»Р°РЅР° РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№ (С‡Р°СЃ)</th>
	<th colspan="2">Р¤РѕСЂРјР° РёС‚РѕРіРѕРІРѕРіРѕ РєРѕРЅС‚СЂРѕР»СЏ</th>
	<th rowspan="2"><?php echo $paginator->sort('РљСѓСЂСЃРѕРІРѕР№ РїСЂРѕРµРєС‚', 'Corriculum.hours_cours_project') ?></th>
	<th rowspan="2"><?php echo $paginator->sort('РљСѓСЂСЃРѕРІР°СЏ СЂР°Р±РѕС‚Р°', 'Corriculum.hours_cours_work') ?></th>
	<th colspan="12">Р Р°СЃРїСЂРµРґРµР»РЅРёРµ Р°СѓРґРёС‚РѕСЂРЅС‹С… Р·Р°РЅСЏС‚РёР№ РїРѕ РєСѓСЂСЃР°Рј Рё СЃРµРјРµСЃС‚СЂР°Рј</th>
	<th></th>
</tr>

<tr>
	<td><b><?php echo $paginator->sort('РўРёРї', 'Discipline.type_id'); ?></b></td>
	<td><b><?php echo $paginator->sort('#', 'Discipline.number'); ?></b></td>
	<td><b><?php echo $paginator->sort('РќР°РёРјРµРЅРѕРІР°РЅРёРµ РґРёСЃС†РёРїР»РёРЅС‹', 'Discipline.title'); ?></b></td>
	
	<td><b><?php echo $paginator->sort('Р’СЃРµРіРѕ', 'Corriculum.hours_total'); ?></b></td>
	<td><b><?php echo $paginator->sort('РђСѓРґРёС‚РѕСЂРЅС‹Рµ', 'Corriculum.hours_auditory'); ?></b></td>
	<td><b><?php echo $paginator->sort('Р›РµРєС†РёРё', 'Corriculum.hours_lections'); ?></b></td>
	<td><b><?php echo $paginator->sort('РџСЂР°РєС‚РёС‡РµСЃРєРёРµ', 'Corriculum.hours_practice'); ?></b></td>
	<td><b><?php echo $paginator->sort('Р›Р°Р±РѕСЂР°С‚РѕСЂРЅС‹Рµ', 'Corriculum.hours_laboratory'); ?></b></td>
	<td><b><?php echo $paginator->sort('РЎР°РјРѕСЃС‚РѕСЏС‚РµР»СЊРЅС‹Рµ', 'Corriculum.hours_independent'); ?></b></td>
	
	<td><b><?php echo $paginator->sort('Р­РєР·Р°РјРµРЅС‹', 'Corriculum.hours_exams'); ?></b></td>
	<td><b><?php echo $paginator->sort('Р—Р°С‡РµС‚С‹', 'Corriculum.hours_credits'); ?></b></td>
	
	<td colspan="2"><b>1 РєСѓСЂСЃ</b></td>
	<td colspan="2"><b>2 РєСѓСЂСЃ</b></td>
	<td colspan="2"><b>3 РєСѓСЂСЃ</b></td>
	<td colspan="2"><b>4 РєСѓСЂСЃ</b></td>
	<td colspan="2"><b>5 РєСѓСЂСЃ</b></td>
	<td colspan="2"><b>6 РєСѓСЂСЃ</b></td>
	
	<td><b><?php echo $paginator->sort('РљРѕРґ РєР°С„РµРґСЂС‹', 'Corriculum.chair_id'); ?></b></td>
</tr>

<?php foreach ($corriculums as $corr) { ?>
<tr>
	<td><?php echo $corr['Discipline']['Type']['title'];?></td>
	<td><?php echo $corr['Discipline']['number'];?></td>
	<td><?php echo $corr['Discipline']['title']; ?></td>
	
	<td><?php echo $corr['0']['hours_total']; ?></td>
	<td><?php echo $corr['Corriculum']['hours_auditory']; ?></td>
	<td><?php echo $corr['Corriculum']['hours_lection']; ?></td>
	<td><?php echo $corr['Corriculum']['hours_practice']; ?></td>
	<td><?php echo $corr['Corriculum']['hours_laboratory']; ?></td>
	<td><?php echo $corr['Corriculum']['hours_independent']; ?></td>
	
	<td><?php echo $corr['Corriculum']['hours_exams']; ?></td>
	<td><?php echo $corr['Corriculum']['hours_credits']; ?></td>
	
	<td><?php echo $corr['Corriculum']['hours_cours_project']; ?></td>
	<td><?php echo $corr['Corriculum']['hours_cours_work']; ?></td>
	
	<?php for ($i=0; $i<($corr['Corriculum']['semester_id'] - 1);$i++) {?><td>&nbsp;</td><?php } ?>
	<td><?php echo $corr['Corriculum']['hours_auditory']; ?></td>
	<?php for ($i=0; $i<(12 - $corr['Corriculum']['semester_id']);$i++) {?><td>&nbsp;</td><?php }?>
	
	<td><?php echo $corr['Chair']['code']; ?></td>
</tr>
<?php } ?>

</table>

<table>
<tr>
	<td><?php echo $paginator->prev('РќР°Р·Р°Рґ'); ?></td>
	<td><?php echo $paginator->numbers(); ?></td>
	<td><?php echo $paginator->next('Р’РїРµСЂРµРґ'); ?></td>
</tr>
</table>

<b>Р”СЂСѓРіРёРµ СЃРїРµС†РёР°Р»РёР·Р°С†РёРё</b>
<ul>
	<?php foreach ($specialites as $spec) {?>
		<li><?php echo $html->link($spec['Specialite']['code'].' - '.$spec['Specialite']['title'], '/corriculums/view/'.$spec['Specialite']['id'].'/1'); ?></li>
	<?php } ?>
</ul>