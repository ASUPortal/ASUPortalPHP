<h2>Студенты без оценок</h2>

{foreach $result as $discipline=>$students}
    <h3>{$discipline}</h3>
    <table>
        {foreach $students as $student}
            <tr><td>{$student}</td></tr>
        {/foreach}
    </table>
{/foreach}