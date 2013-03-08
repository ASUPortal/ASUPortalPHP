<h2>Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ РґРёСЃС†РёРїР»РёРЅС‹</h2>

<?php echo $form->create('Discipline', array('action' => 'edit')); ?>
<table>
<tr>
	<td><b>РќР°Р·РІР°РЅРёРµ:</b></td>
	<td><?php echo $form->input('title', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РўРёРї:</b></td>
	<td><?php echo $form->input('type_id', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РќРѕРјРµСЂ:</b></td>
	<td><?php echo $form->input('number', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РџСѓР±Р»РёРєР°С†РёСЏ:</b></td>
	<td><?php echo $form->input('pub', array('label' => '')); ?></td>
</tr>

<tr>
	<td colspan="2"><?php echo $form->end('РЎРѕС…СЂР°РЅРёС‚СЊ'); ?></td>
</tr>
</table>