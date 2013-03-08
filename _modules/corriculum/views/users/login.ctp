<h1>РђРІС‚РѕСЂРёР·Р°С†РёСЏ</h1>

<?php echo $form->create('User', array('action' => 'login')); ?>

<table>
<tr>
	<td><b>Р›РѕРіРёРЅ</b></td>
	<td><?php echo $form->input('login', array('label' => '')); ?></td>
</tr>

<tr>
	<td><b>РџР°СЂРѕР»СЊ</b></td>
	<td><?php echo $form->input('password', array('label' => '')); ?></td>
</tr>

<tr>
	<td><?php echo $form->end('Р’РѕР№С‚Рё'); ?></td>
</tr>
</table>