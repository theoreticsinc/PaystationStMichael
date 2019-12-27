<?php
	function filter($data)
	{
	   
	   $data = trim($data); //Removes whitespace or other predefined characters from the left side of a string  ///////// rtrim() - Removes whitespace or other predefined characters from the right side of a string
	   $data = stripslashes($data); ///stripslashes() function removes backslashes 
	   $data = htmlspecialchars($data); ///converts some predefined characters to HTML entities //To convert special HTML entities back to characters, use the htmlspecialchars_decode() function
	   $data= htmlentities($data);
	   return $data;

	}
	
	function is_email($mail)
	{
		$regex = '/([a-z0-9_]+|[a-z0-9_]+\.[a-z0-9_]+)@(([a-z0-9]|[a-z0-9]+\.[a-z0-9]+)+\.([a-z]{2,4}))/i';
		return preg_match($regex, $mail);
	}

function has_null()
{
	$status = false;
	for($x = 0; $x <func_num_args(); $x++)
	{
		if(func_get_arg($x) == "" ) return true;
	}
}
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo '<script>console.log( "Debug Objects: ' . $output . '" );</script>';
}

?>