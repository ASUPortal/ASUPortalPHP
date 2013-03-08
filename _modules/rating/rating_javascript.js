function Delete() 
{ 
   if(confirm("Вы уверены что хотите удалить?")) 
     { 
        document.myForm.submit(); 
     } 
     else {
	 document.getElementById('del').name=false;
	}

	
}
function toggleCheckAll(blnCheck) {
var el;
for( var i=0; i<document.forms["myForm"].elements.length; i++ ) {
el = document.forms["myForm"].elements[i];
if( el.type == "checkbox")
 {
el.checked = blnCheck;
}}
}

function markAllRows( container_id ) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;

    for ( var i = 0; i < rows.length; i++ ) {

        checkbox = rows[i].getElementsByTagName( 'input' )[0];

        if ( checkbox && checkbox.type == 'checkbox' ) {
            unique_id = checkbox.name + checkbox.value;
            if ( checkbox.disabled == false ) {
                checkbox.checked = true;
                if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
                    rows[i].className += ' marked';
                    marked_row[unique_id] = true;
                }
            }
        }
    }

    return true;
}