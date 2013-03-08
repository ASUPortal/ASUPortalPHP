<h2>РўРёРїС‹</h2>

<table>
<tr>
	<th><?php echo $paginator->sort('#', 'id'); ?></th>
	<th><?php echo $paginator->sort('РќР°Р·РІР°РЅРёРµ', 'title'); ?></th>
	<th><?php echo $paginator->sort('РџСѓР±Р»РёРєР°С†РёСЏ', 'pub'); ?></th>
	<th>Р”РµР№СЃС‚РІРёРµ</th>
</tr>

<?php foreach ($types as $type) { ?>
<tr>
	<td><?php echo $type['Type']['id']?></td>
	<td><?php echo $type['Type']['title']?></td>
	<td><?php echo $type['Type']['pub']?></td>
	<td><?php echo $html->link('РџСЂР°РІРєР°', '/admin/types/edit/'.$type['Type']['id']); ?> &nbsp; <?php echo $html->link('РЈРґР°Р»РёС‚СЊ', '/admin/types/del/'.$type['Type']['id']); ?></td>
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

<?php echo $html->link('Р”РѕР±Р°РІРёС‚СЊ С‚РёРї', '/admin/types/edit/'); ?>