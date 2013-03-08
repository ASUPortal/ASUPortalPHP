<h2>РљР°С„РµРґСЂС‹</h2>

<table>
<tr>
	<th>#</th>
	<th>РќР°Р·РІР°РЅРёРµ</th>
	<th>РџСѓР±Р»РёРєР°С†РёСЏ</th>
	<th>Р”РµР№СЃС‚РІРёРµ</th>
</tr>

<?php foreach ($chairs as $chair) { ?>
<tr>
	<td><?php echo $chair['Chair']['id']?></td>
	<td><?php echo $chair['Chair']['title']?></td>
	<td><?php echo $chair['Chair']['pub']?></td>
	<td><?php echo $html->link('РџСЂР°РІРєР°', '/admin/chairs/edit/'.$chair['Chair']['id']); ?> &nbsp; <?php echo $html->link('РЈРґР°Р»РёС‚СЊ', '/admin/chairs/del/'.$chair['Chair']['id']); ?></td>
</tr>
<?php } ?>
</table>

<?php echo $html->link('Р”РѕР±Р°РІРёС‚СЊ РєР°С„РµРґСЂСѓ', '/admin/chairs/edit/'); ?>