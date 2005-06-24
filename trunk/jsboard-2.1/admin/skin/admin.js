<!--
var child = null;
var count = 0;

function fork ( type , url ) {
  var childname = 'BoardManager' + count++;

  if(child != null) {    // child was created before.
  if(!child.closed) {  // if child window is still opened, close window.
    child.close();
    }
  }
  // here, we can ensure that child window is closes.
  if(type == 'popup' ) {
    child = window.open(url, childname,
            'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=650,height=500');
  } else if(type == 'popup1' ) {
    child = window.open(url, childname,
            'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=300');
  } else alert('Fatal : in function fork()');
  return;
}

function logout () {
  document.location='../session.php?m=logout';
}
//-->
