<?php

/************************************************************************
*                                                                       *
*                 OOPS Administration Center v1.2                       *
*                     Scripted by JoungKyun Kim                         *
*               admin@oops.org http://www.oops.org                      *
*                                                                       *
************************************************************************/

//include("./include/user_info.ph");
include("./include/html_head.ph");

echo ("
<form name=auth method=POST action=cookie.php3>
패스워드를 넣으세요<br>
<input type=password name=login_pass id=input>
<input type=hidden name=mode value=login>
</form>

<form name=reset method=POST action=cookie.php3>
<input type=hidden name=mode value=logout>
패스워드 초기화 <input type=submit name=reset value=reset id=input>
</form>

");

include("./include/html_tail.ph");

?>