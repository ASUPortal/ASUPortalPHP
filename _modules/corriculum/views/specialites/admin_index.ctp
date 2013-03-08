<h2>РЎРїРµС†РёР°Р»РёР·Р°С†РёСЏ</h2>

<table>
<tr>
	<th><?php echo $paginator->sort('#', 'id'); ?></th>
	<th><?php echo $paginator->sort('РќР°Р·РІР°РЅРёРµ', 'title'); ?></th>
	<th><?php echo $paginator->sort('РљРѕРґ', 'code'); ?></th>
	<th><?php echo $paginator->sort('РџСѓР±Р»РёРєР°С†РёСЏ', 'pub'); ?></th>
	<th>Р”РµР№СЃС‚РІРёРµ</th>
</tr>

<?php foreach ($specialites as $specialite) { ?>
<tr>
	<td><?php echo $specialite['Specialite']['id']?></td>
	<td><?php echo $specialite['Specialite']['title']?></td>
	<td><?php echo $specialite['Specialite']['code']?></td>
	<td><?php echo $specialite['Specialite']['pub']?></td>
	<td><?php echo $html->link('РџСЂР°РІРєР°', '/admin/specialites/edit/'.$specialite['Specialite']['id']); ?> &nbsp; <?php echo $html->link('РЈРґР°Р»РёС‚СЊ', '/admin/specialites/del/'.$specialite['Specialite']['id']); ?></td>
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

<?php echo $html->link('Р”РѕР±Р°РІРёС‚СЊ СЃРїРµС†РёР°Р»РёР·Р°С†РёСЋ', '/admin/specialites/edit/'); ?>