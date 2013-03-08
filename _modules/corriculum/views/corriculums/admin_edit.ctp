<h2>Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ СЌР»РµРјРµРЅС‚Р° СѓС‡РµР±РЅРѕРіРѕ РїР»Р°РЅР°</h2>

<?php echo $form->create('Corriculum', array('action' => 'edit')); ?>
<table>
<tr>
	<td><b>РџСѓР±Р»РёРєР°С†РёСЏ:</b></td>
	<td><?php echo $form->input('pub', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>Р¤РѕСЂРјР° РѕР±СѓС‡РµРЅРёСЏ:</b></td>
	<td><?php echo $form->input('form_id', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>Р¦РёРєР»:</b></td>
	<td><?php echo $form->input('cycle_id', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РЎРїРµС†РёР°Р»СЊРЅРѕСЃС‚СЊ:</b></td>
	<td><?php echo $form->input('specialite_id', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>Р”РёСЃС†РёРїР»РёРЅР°:</b></td>
	<td><?php echo $form->input('discipline_id', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РљР°С„РµРґСЂР°:</b></td>
	<td><?php echo $form->input('chair_id', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РЎРµРјРµСЃС‚СЂ:</b></td>
	<td><?php echo $form->input('semester_id', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РђСѓРґРёС‚РѕСЂРЅС‹С… Р·Р°РЅСЏС‚РёР№:</b></td>
	<td><?php echo $form->input('hours_auditory', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>Р›РµРєС†РёРѕРЅРЅС‹С… Р·Р°РЅСЏС‚РёР№:</b></td>
	<td><?php echo $form->input('hours_lection', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РџСЂР°РєС‚РёС‡РµСЃРєРёС… Р·Р°РЅСЏС‚РёР№:</b></td>
	<td><?php echo $form->input('hours_practice', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>Р›Р°Р±РѕСЂР°С‚РѕСЂРЅС‹С… Р·Р°РЅСЏС‚РёР№:</b></td>
	<td><?php echo $form->input('hours_laboratory', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РЎР°РјРѕСЃС‚РѕСЏС‚РµР»СЊРЅР°СЏ СЂР°Р±РѕС‚Р°:</b></td>
	<td><?php echo $form->input('hours_independent', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>Р­РєР·Р°РјРµРЅ:</b></td>
	<td><?php echo $form->input('hours_cours_project', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>Р—Р°С‡РµС‚:</b></td>
	<td><?php echo $form->input('hours_cours_work', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РљСѓСЂСЃРѕРІРѕРµ РїСЂРѕРµРєС‚РёСЂРѕРІР°РЅРёРµ:</b></td>
	<td><?php echo $form->input('hours_auditory', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РљСѓСЂСЃРѕРІР°СЏ СЂР°Р±РѕС‚Р°:</b></td>
	<td><?php echo $form->input('hours_auditory', array('label' => '')); ?></td>
</tr>

<tr>
	<td colspan="2"><?php echo $form->end('РЎРѕС…СЂР°РЅРёС‚СЊ'); ?></td>
</tr>
</table>