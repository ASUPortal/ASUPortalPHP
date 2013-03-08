<h2>РџРѕР»СЊР·РѕРІР°С‚РµР»Рё</h2>

<table>
<tr>
	<th><?php echo $paginator->sort('#', 'id'); ?></th>
	<th><?php echo $paginator->sort('Р›РѕРіРёРЅ', 'login'); ?></th>
	<th>Р”РµР№СЃС‚РІРёРµ</th>
</tr>

<?php foreach ($users as $user) { ?>
<tr>
	<td><?php echo $user['User']['id']?></td>
	<td><?php echo $user['User']['login']?></td>
	<td><?php echo $html->link('РџСЂР°РІРєР°', '/admin/users/edit/'.$user['User']['id']); ?> &nbsp; <?php echo $html->link('РЈРґР°Р»РёС‚СЊ', '/admin/users/del/'.$user['User']['id']); ?></td>
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

<?php echo $html->link('Р”РѕР±Р°РІРёС‚СЊ РїРѕР»СЊР·РѕРІР°С‚РµР»СЏ', '/admin/users/add/'); ?>