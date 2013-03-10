<p>
{CHtml::activeLabel("work_current", $student)}
                {CHtml::activeTextBox("work_current", $student)}
                {CHtml::error("work_current", $student)}
</p>

<p>
{CHtml::activeLabel("work_proposed", $student)}
                {CHtml::activeTextBox("work_proposed", $student)}
                {CHtml::error("work_proposed", $student)}
</p>

<p>
{CHtml::activeLabel("comment", $student)}
                {CHtml::activeTextBox("comment", $student)}
                {CHtml::error("comment", $student)}
</p>