<h2>Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ РєР°С„РµРґСЂС‹</h2>

<?php echo $form->create('Chair', array('action' => 'edit')); ?>
<table>
<tr>
	<td><b>РќР°Р·РІР°РЅРёРµ:</b></td>
	<td><?php echo $form->input('title', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РљРѕРґ:</b></td>
	<td><?php echo $form->input('code', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РџСѓР±Р»РёРєР°С†РёСЏ:</b></td>
	<td><?php echo $form->input('pub', array('label' => '')); ?></td>
</tr>

<tr>
	<td colspan="2"><?php echo $form->end('РЎРѕС…СЂР°РЅРёС‚СЊ'); ?></td>
</tr>
</table>