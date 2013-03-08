<h2>Р¦РёРєР»С‹</h2>

<table>
<tr>
	<th>#</th>
	<th>РќР°Р·РІР°РЅРёРµ</th>
	<th>РџСѓР±Р»РёРєР°С†РёСЏ</th>
	<th>Р”РµР№СЃС‚РІРёРµ</th>
</tr>

<?php foreach ($cycles as $cycle) { ?>
<tr>
	<td><?php echo $cycle['Cycle']['id']?></td>
	<td><?php echo $cycle['Cycle']['title']?></td>
	<td><?php echo $cycle['Cycle']['pub']?></td>
	<td><?php echo $html->link('РџСЂР°РІРєР°', '/admin/cycles/edit/'.$cycle['Cycle']['id']); ?> &nbsp; <?php echo $html->link('РЈРґР°Р»РёС‚СЊ', '/admin/cycles/del/'.$cycle['Cycle']['id']); ?></td>
</tr>
<?php } ?>
</table>

<?php echo $html->link('Р”РѕР±Р°РІРёС‚СЊ С†РёРєР»', '/admin/cycles/edit/'); ?>