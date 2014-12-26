<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>4DX Baseline</title>
    </head>
    <body>
        <div><h2>4DX Independence and Responsibility <br>
                Evaluating Daily Class Activity</h2>></div>
        <ul> 
        <?php for($i=1;$i<=5;$i++){ ?>
        <li>Menu Item <?php echo $i; ?></li> 
        <?php } ?>
        
$var1 = "Hello World!"; 
$var2 = "We are silly!"; 
$htmlOutput = ''; // set as empty, but defined 

// notice the period before each ( = ) sign... that is concatenating them onto the original var value
$htmlOutput .= '<table bgcolor="#663366" cellpadding="8">'; 
$htmlOutput .= '<tr>'; 
$htmlOutput .= '<td bgcolor="#CCCCCC">'; 
$htmlOutput .= ' ' . $var1 . ' '; 
$htmlOutput .= '</td>'; 
$htmlOutput .= '<td bgcolor="#FFFF00">'; 
$htmlOutput .= ' ' . $var2 . ' '; 
$htmlOutput .= '</td>'; 
$htmlOutput .= '</tr>'; 
$htmlOutput .= "</table>"; 
// Now print or echo the output 
echo "$htmlOutput "; 
        </ul> 
    </body>
</html>
