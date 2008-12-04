function navInit () {
  this.name = '';
  this.version = '';
  this.core = 'Mozilla';
}

function browserType() {
  var navs = navigator.userAgent;
  var navsver_t = '';
  var navsver = '';
  nav = new navInit;

  if ( navs.indexOf('MSIE') != -1 ) {
    nav.name = 'MSIE';
    navsver_t = navs.search(/MSIE ([^;]+)/);
    navsver   = RegExp.$1;
    nav.core  = 'MSIE';
  } else if ( navs.indexOf('Firefox') != -1 ) {
    nav.name  = 'Firefox';
    navsver_t = navs.search(/([^\/]+)$/);
    navsver   = RegExp.$1;
    nav.core  = 'Mozilla';
  } else if ( navs.indexOf('Thunderbird') != -1 ) {
    nav.name  = 'Thunderbird';
    navsver_t = navs.search(/([^\/]+)$/);
    navsver   = RegExp.$1;
    nav.core  = 'Mozilla';
  } else if ( navs.indexOf('Safari') != -1 ) {
    nav.name  = 'Safari';
    navsver_t = navs.search(/([^\/]+)$/);
    navsver   = RegExp.$1;
  } else if ( navs.indexOf('Opera') != -1 ) {
    nav.name  = 'Opera';
    navsver_t = navs.search(/Opera\/([0-9.]+)/);
    navsver   = RegExp.$1;
  } else if ( navs.indexOf('Konqueror') != -1 ) {
    nav.name  = 'Konqueror';
    navsver_t = navs.search(/Konqueror\/([0-9.]+)/);
    navsver   = RegExp.$1;
  } else if ( navs.indexOf('Mozilla') != -1 ) {
    /* over Netscape 6 */
    if ( navs.indexOf('Netscape') != -1 ) {
      nav.name  = 'Netscape';
      navsver_t = navs.search(/([^\/]+)$/);
      navsver   = RegExp.$1;
    /* Mozilla comportable browser */
    } else if ( navs.indexOf('Gecko') != -1 ) {
      nav.name = 'Mozilla';
      if ( navs.match(/rv:/) ) {
        navsver_t = navs.search(/rv:*([^)]+)\)/);
      } else {
        navsver_t = navs.search(/ (m[0-9]+)/);
      }
      navsver   = RegExp.$1;
    /* Nescape Browser */
    } else {
      nav.name  = 'Netscape';
      navsver_t = navs.search(/Mozilla\/([0-9.]+)/);
      navsver   = RegExp.$1;
      nav.core  = 'Netscape';
    }
  } else {
    nav.name  = 'Netscape';
    nav.core  = 'Netscape';
  }

  nav.version = navsver.replace(/[ ]+\([^)]+\)$/g,"");
  return nav;
}
var nav = browserType();

function newwinInit() {
  this.child = null;
  this.count = 0;
}

ns = new newwinInit;

function new_windows(addr,tag,scroll,resize,wid,hei) {
  if (self.screen) {
    width = screen.width
    height = screen.height
  } else if (self.java) {
    var def = java.awt.Toolkit.getDefaultToolkit();
    var scrsize = def.getScreenSize();
    width = scrsize.width;
    height = scrsize.width;
  }

  var chkwid = width - 10
  var chkhei = height - 20

  if (chkwid < wid) {
    wid = width - 5
    if(chkhei < hei) { hei = height - 60 }
    scroll = 'yes'
  }

  if (chkhei < hei) {
    if(chkwid < wid) { wid = width - 5 }
    hei = height - 60
    scroll = 'yes'
  }

  var childname = 'JSBoard' + ns.count++;
  // if child window is opend, close child window.
  if(ns.child != null) {
    if(!ns.child.closed) { ns.child.close(); }
  }
  ns.child = window.open(addr,tag,'left=0, top=0, toolbar=0,scrollbars=' + scroll + ',status=0,menubar=0,resizable=' + resize + ',width=' + wid + ',height=' + hei +'');
  // if child window load, change window focus topest
  ns.child.focus();
  return;
}

function sendform (user, addr, name) {
  var _nf;
  var _nb;

  _nf = name ? name + " <" : '';
  _nb = name ? ">" : '';

  location.href="mailto:" + _nf + user + "@" + addr + _nb;
}

function onMouseColor(id,classname) {
  document.getElementById(id).className = classname;
}

function InputFocus(id) {
  document.getElementById(id).focus();
}

function trim(_str) {
  var result;

  if ( ! _str ) {
    return("");
  }

  result = _str.replace(/^\s+/g, '');
  result = _str.replace(/\s+$/g, '');

  return(result);
}

function registCheck() {
  doc = document;

  id = trim(doc.getElementById('name').value);

  if ( id.length < 1 ) {
    alert('Please writer\'s name is required');
    return false;
  }

  title  = trim(doc.getElementById('title').value);
  if ( title.length < 1 ) {
    alert('Please subject of article is required');
    return false;
  }
  passwd = trim(doc.getElementById('passwd').value);
  if ( passwd.length < 1 ) {
    alert('Please password of article is required');
    return false
  }

  return true;
}

function location_ref(url) {
  var fakeLink = document.createElement ("a");
  if (typeof(fakeLink.click) == 'undefined') {
    location.href = url;  // sends referrer in FF, not in IE
  } else {
    fakeLink.href = url;
    document.body.appendChild(fakeLink);
    fakeLink.click();   // click() method defined in IE only
  }
}
