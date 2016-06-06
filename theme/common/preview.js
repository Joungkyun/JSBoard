/*
 * $Id: preview.js,v 1.5 2009-11-16 21:52:47 oops Exp $
 */

function previewInit() {
  this.x = 0;
  this.y = 0;
  this.snow = 0;
  this.sw = 0;
  this.cnt = 0;
  this.dir = 1;
  this.offsetx = 3;
  this.offsety = 3;
  this.width = 260;
  this.height = 50;

  try {
    if ( ! nav ) { nav = browserType (); }
  } catch (e) {
    nav = browserType ();
  }
}

pv = new previewInit;
if ( nav.core == 'Netscape' ) {
    over = document.overDiv;
} else {
  over = document.getElementById('overDiv');
}
document.onmousemove = mouseMove;
if ( nav.core != 'MSIE' ) {
  if (document.addEventListener) {
    document.addEventListener("move", mouseMove, false);
  } else {
    document.captureEvents(Event.MOUSEMOVE);
  }
}

function drs(text, title) { dts(1,text); }

function nd() {
  if ( pv.cnt >= 1 ) { pv.sw = 0 };
  if ( pv.sw == 0 ) {
      pv.snow = 0;
      hideObject(over);
  } else { pv.cnt++; }
}

function dts(d,text) {
  txt = "<div class=\"previewload\">" +
        text + "<\/div>";
  layerWrite(txt);
  pv.dir = d;
  disp();
}

function disp() {
  if (pv.snow == 0) {
    if (pv.dir == 2) { moveTo(over,pv.x+pv.offsetx-(pv.width/2),pv.y+pv.offsety); } // Center
    if (pv.dir == 1) { moveTo(over,pv.x+pv.offsetx,pv.y+pv.offsety); } // Right
    if (pv.dir == 0) { moveTo(over,pv.x-pv.offsetx-pv.width,pv.y+pv.offsety); } // Left
    showObject(over);
    pv.snow = 1;
  }
}

function mouseMove(e) {
  if ( nav.core == 'MSIE' ) {
    pv.x = event.x + document.body.scrollLeft + 10
    pv.y = event.y + document.body.scrollTop
    if ( pv.x + pv.width - document.body.scrollLeft > document.body.clientWidth ) pv.x = pv.x - pv.width - 25;
    if ( pv.y + pv.height - document.body.scrollTop > document.body.clientHeight ) pv.y = pv.y - pv.height;
  } else if ( nav.core == 'Mozilla' ) {
    pv.x = ( (e.pageX)+pv.width-window.pageXOffset > window.innerWidth ) ? (e.pageX+10)-pv.width-10 : e.pageX+10;
    pv.y = ( (e.pageY)+pv.height-self.pageYOffset > window.innerHeight ) ? (e.pageY)-pv.height+5 : e.pageY;
  } else {
    pv.x=e.pageX+10;
    pv.y=e.pageY;
    if (pv.x+pv.width-self.pageXOffset > window.innerWidth) pv.x=pv.x-pv.width-5;
    if (pv.y+pv.height-self.pageYOffset > window.innerHeight) pv.y=pv.y-pv.height;
  }

  if (pv.snow) {
    if (pv.dir == 2) { moveTo(over,pv.x+pv.offsetx-(pv.width/2),pv.y+pv.offsety); } // Center
    if (pv.dir == 1) { moveTo(over,pv.x+pv.offsetx,pv.y+pv.offsety); } // Right
    if (pv.dir == 0) { moveTo(over,pv.x-pv.offsetx-pv.width,pv.y+pv.offsety); } // Left
  }
}

function cClick() { hideObject(over); pv.sw=0; }
function layerWrite(txt) {
  if ( nav.core == 'Netscape' ) {
    var lyr = document.overDiv.document;
    lyr.write(txt);
    lyr.close();
  } else {
    over.innerHTML = txt;
  }
}
function showObject(obj) {
  if ( nav.core == 'Netscape' ) {
    obj.visibility = "show"
  } else {
    obj.style.visibility = "visible";
  }
}
function hideObject(obj) {
  if ( nav.core == 'Netscape' ) {
    obj.visibility = "hide"
  } else {
    obj.style.visibility = "hidden";
  }
}
function moveTo(obj,xL,yL) {
  if ( nav.core == 'Netscape' ) {
    obj.left = xL;
    obj.top = yL;
  } else {
    obj.style.left = xL;
    obj.style.top = yL;
  }
}

