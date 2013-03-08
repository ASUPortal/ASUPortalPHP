<h2>Р”РёСЃС†РёРїР»РёРЅС‹</h2>

<table>
<tr>
	<th><?php echo $paginator->sort('#', 'id'); ?></th>
	<th><?php echo $paginator->sort('РќР°Р·РІР°РЅРёРµ', 'title'); ?></th>
	<th><?php echo $paginator->sort('РўРёРї', 'type_id'); ?></th>
	<th><?php echo $paginator->sort('РџСѓР±Р»РёРєР°С†РёСЏ', 'pub'); ?></th>
	<th>Р”РµР№СЃС‚РІРёРµ</th>
</tr>

<?php foreach ($disciplines as $discipline) { ?>
<tr>
	<td><?php echo $discipline['Discipline']['id']?></td>
	<td><?php echo $discipline['Discipline']['title']?></td>
	<td><?php echo $discipline['Type']['title']?></td>
	<td><?php echo $discipline['Discipline']['pub']?></td>
	<td><?php echo $html->link('РџСЂР°РІРєР°', '/admin/disciplines/edit/'.$discipline['Discipline']['id']); ?> &nbsp; <?php echo $html->link('РЈРґР°Р»РёС‚СЊ', '/admin/disciplines/del/'.$discipline['Discipline']['id']); ?></td>
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

<?php echo $html->link('Р”РѕР±Р°РІРёС‚СЊ РґРёСЃС†РёРїР»РёРЅСѓ', '/admin/disciplines/edit/'); ?>