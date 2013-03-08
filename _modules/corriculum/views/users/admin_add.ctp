<h2>РЎРѕР·РґР°РЅРёРµ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ</h2>

<?php echo $form->create('User', array('action' => 'edit')); ?>
<table>
<tr>
	<td><b>Р›РѕРіРёРЅ:</b></td>
	<td><?php echo $form->input('login', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РџР°СЂРѕР»СЊ:</b></td>
	<td><?php echo $form->input('password', array('label' => '')); ?></td>
</tr>

<tr>
	<td colspan="2"><?php echo $form->end('РЎРѕС…СЂР°РЅРёС‚СЊ'); ?></td>
</tr>
</table>