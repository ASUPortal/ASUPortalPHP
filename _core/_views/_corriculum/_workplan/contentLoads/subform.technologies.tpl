<tr>
    <td colspan="6"><b>Образовательные технологии</b></td>
</tr>
{foreach $object->technologies as $technology}
    <tr>
        <td>{$technology->getId()}</td>
    </tr>
{/foreach}