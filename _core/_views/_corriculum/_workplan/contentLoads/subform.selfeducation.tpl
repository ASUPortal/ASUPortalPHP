<tr>
    <td colspan="6"><b>Самостоятельное изучение</b></td>
</tr>
{foreach $object->selfEducations as $selfEducation}
    <tr>
        <td>{$selfEducation->getId()}</td>
    </tr>
{/foreach}