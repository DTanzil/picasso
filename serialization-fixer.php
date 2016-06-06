<?php

// This code is a serialised string fixer for WordPress (and probably other systems).
// Simply select the table you need to fix in $table, and the code will change the string lengths for you.  Saves having to manually go through.
// Written 20090302 by David Coveney http://www.davecoveney.com and released under the WTFPL - ie, do what ever you want with the code, and I take no responsibility for it OK?
// To view the WTFPL go to http://sam.zoy.org/wtfpl/ (WARNING: it's a little rude, if you're sensitive)
//
// Thanks go to getmequick at gmail dot com who years ago posted up his preg_replace at http://uk2.php.net/unserialize and saved me trying to work it out.
//
// Before you start, do make a backup.  A backup that you know works, because this code has the scope to really break your data if you're careless.
//
// Oh, and the code doesn't care if there's serialised data in there or not - it'll still try and fix it.
// Why?  Because it's a quick tool written for me, I'm sharing it to be helpful.
//
// I repeat - if you kill your data with this, it's not my problem.  You use this entirely at your own risk.

// a more elegant solution, by the way, might be to write a nice search and replace that would work on an SQL dump and reset the serialised strings.
// if you sort one out, do let me know....


// connect to DB - the fields are obvious.  If you need to think about it too much you probably shouldn't be playing with this code.

$host = 'localhost';        // normally localhost, but not necessarily.
$usr  = 'root';        // your db userid
$pwd  = '';                 // your db password
$db   = 'picasso_live';        // your database

$table = 'wp_options';    // the table you need to fix
$column = 'option_value';   // the column with the serialised data in it
$index_column = 'option_id';// the 


/*$table = 'wp_postmeta';    // the table you need to fix
$column = 'meta_value';   // the column with the serialised data in it
$index_column = 'meta_id';// the */

$cid = mysqli_connect($host,$usr,$pwd, $db); 

if (!$cid) { echo("Connecting to DB Error: " . mysql_error() . "<br/>"); }

// now let's get the data...

$SQL = "SELECT * FROM ".$table;
$retid = mysqli_query($cid, $SQL);

if (!$retid) { echo( mysql_error()); }


while ($row = mysqli_fetch_array($retid)) {
    $value_to_fix = $row[$column];
    $index = $row[$index_column];

    // don't need to output everything, uncomment if you want to see, but don't be surprised if some browsers break!

//    echo ('changing option_id: '.$index.'<br/>');
//    echo ('before: '.$value_to_fix.'<br/>');
    $fixed_value = __recalcserializedlengths($value_to_fix);
//    echo ('after: '.$fixed_value.'<br/>');
    
    // now let's create the update query...
    
    $UPDATE_SQL = "UPDATE ".$table." SET ".$column." = '".mysql_real_escape_string($fixed_value)."' WHERE ".$index_column." = '".$index."'";
    
//    echo 'update SQL - '.$UPDATE_SQL.'<br/><br/>';
    
    // and run it!  Autocommit seems to be the norm with mySQL setups, so none of that here.  You may need to add it if you mod for Oracle or SQLServer.

    $result = mysqli_query($cid,"$UPDATE_SQL");
    
    if (!$result) {
        echo("ERROR: " . mysql_error() . "<br/>$SQL<br/>"); } 

}

mysql_close($cid); 


function __recalcserializedlengths($sObject) {
   
    $__ret =preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $sObject );

    // return preg_replace_callback('/(^|_)([a-z])/', 
    //    create_function ('$matches', 'return strtoupper($matches[2]);'), $word);
    
    // return unserialize($__ret);
   return $__ret;
}

?>