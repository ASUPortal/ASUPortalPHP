<h2>РЈС‡РµР±РЅС‹Р№ РїР»Р°РЅ</h2>

<table>
<tr>
	<th>Р”РµР№СЃС‚РІРёРµ</th>
	<th><?php echo $paginator->sort('#', 'id'); ?></th>
	<th><?php echo $paginator->sort('Р¦РёРєР»', 'cycle.title'); ?></th>
	<th><?php echo $paginator->sort('Р”РёСЃС†РёРїР»РёРЅР°', 'discipline.title'); ?></th>
	<th>РЎРїРµС†РёР°Р»СЊРЅРѕСЃС‚СЊ</th>
	<th>РђСѓРґРёС‚РѕСЂРЅС‹С… Р·Р°РЅСЏС‚РёР№</th>
	<th>Р›РµРєС†РёРѕРЅРЅС‹С… Р·Р°РЅСЏС‚РёР№</th>
	<th>РџСЂР°РєС‚РёС‡РµСЃРєРёС… Р·Р°РЅСЏС‚РёР№</th>
	<th>Р›Р°Р±РѕСЂР°С‚РѕСЂРЅС‹С… Р·Р°РЅСЏС‚РёР№</th>
	<th>РЎР°РјРѕСЃС‚РѕСЏС‚РµР»СЊРЅР°СЏ СЂР°Р±РѕС‚Р°</th>
	<th>Р­РєР·Р°РјРµРЅ</th>
	<th>Р—Р°С‡РµС‚</th>
	<th>РљСѓСЂСЃРѕРІРѕРµ РїСЂРѕРµРєС‚РёСЂРѕРІР°РЅРёРµ</th>
	<th>РљСѓСЂСЃРѕРІР°СЏ СЂР°Р±РѕС‚Р°</th>
	<th>РљР°С„РµРґСЂР°</th>
	<th>РЎРµРјРµСЃС‚СЂ</th>
	<th>Р¤РѕСЂРјР° РѕР±СѓС‡РµРЅРёСЏ</th>
	<th><?php echo $paginator->sort('РџСѓР±Р»РёРєР°С†РёСЏ', 'pub'); ?></th>
</tr>

<?php foreach ($corriculums as $corriculum) { ?>
<tr>
<td><?php echo $html->link('РџСЂР°РІРєР°', '/admin/corriculums/edit/'.$corriculum['Corriculum']['id']); ?> &nbsp; <?php echo $html->link('РЈРґР°Р»РёС‚СЊ', '/admin/corriculums/del/'.$corriculum['Corriculum']['id']); ?></td>
	<td><?php echo $corriculum['Corriculum']['id']?></td>
	<td><?php echo $corriculum['Cycle']['title']?></td>
	<td><?php echo $corriculum['Discipline']['title']?></td>
	<td><?php echo $corriculum['Specialite']['title']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_auditory']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_lection']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_practice']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_laboratory']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_independent']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_exams']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_credits']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_cours_project']?></td>
	<td><?php echo $corriculum['Corriculum']['hours_cours_work']?></td>
	<td><?php echo $corriculum['Chair']['title']?></td>
	<td><?php echo $corriculum['Semester']['title']?></td>
	<td><?php echo $corriculum['Form']['title']?></td>
	<td><?php echo $corriculum['Corriculum']['pub']?></td>
</tr>
<?php } ?>
</table>

<table align="center">
<tr>
	<td><?php echo $paginator->prev('<< РќР°Р·Р°Рґ');?></td>
	<td><?php echo $paginator->numbers(); ?></td>
	<td><?php echo $paginator->next('Р”Р°Р»РµРµ >>');?></td>
</tr>
</table>

<?php echo $html->link('Р”РѕР±Р°РІРёС‚СЊ СЌР»РµРјРµРЅС‚ СѓС‡РµР±РЅРѕРіРѕ РїР»Р°РЅР°', '/admin/corriculums/edit/'); ?>