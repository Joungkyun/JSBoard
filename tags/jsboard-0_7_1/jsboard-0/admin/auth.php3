<?php

include("./include/html_head.ph");

include("../include/db.ph") ;
include("./include/boardinfo.ph") ;

$lang = superpass_info($lang) ;

include("../include/multi_lang.ph") ;

echo ("
<form name=auth method=POST action=cookie.php3>
$ment<br>
<input type=password name=login_pass id=input>
<input type=hidden name=mode value=login>
</form>

<form name=reset method=POST action=cookie.php3>
<input type=hidden name=mode value=logout>
$ment1 <input type=submit name=reset value=reset id=input>
</form>

");

include("./include/html_tail.ph");

?>