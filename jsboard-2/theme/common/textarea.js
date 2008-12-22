// $Id: textarea.js,v 1.3 2008-12-22 14:51:13 oops Exp $
// $origId: textarea.js,v 1.9 2006/04/14 13:48:56 killes Exp $
// from drupal
// many functions are imported from drupal.js to eliminate dependency

if (document.jsEnabled == undefined) {
  // Note: ! casts to boolean implicitly.
  document.jsEnabled = !(
   !document.getElementsByTagName ||
   !document.createElement        ||
   !document.createTextNode       ||
   !document.getElementById);
}

function textarea_cols(obj) {
  if ( /%/.test(obj.style.width) ) {
    if ( /%/.test(tarea_width) ) { width = browserSize('width'); }
    else { width = tarea_width; }
  } else {
    width = obj.style.width;
  }

  wtype = typeof width;
  if ( wtype == 'string' )
    width = width.replace(/[ ]*px/g, "");

  if ( ! width || obj.cols == -1 || obj.cols == 20 ) {
    obj.cols = tarea_cols;
    //document.getElementById('vcols').value = obj.cols;
    return true;
  }

  obj.cols = Math.round(width / 7) + 7;
  //document.getElementById('vcols').value = obj.cols;

  return true;
}

/**
 * Retrieves the absolute position of an element on the screen
 */
function absolutePosition(el) {
  var sLeft = 0, sTop = 0;
  var isDiv = /^div$/i.test(el.tagName);
  if (isDiv && el.scrollLeft) {
    sLeft = el.scrollLeft;
  }
  if (isDiv && el.scrollTop) {
    sTop = el.scrollTop;
  }
  var r = { x: el.offsetLeft - sLeft, y: el.offsetTop - sTop };
  if (el.offsetParent) {
    var tmp = absolutePosition(el.offsetParent);
    r.x += tmp.x;
    r.y += tmp.y;
  }
  return r;
};

function dimensions(el) {
  return { width: el.offsetWidth, height: el.offsetHeight };
}

/**
 * Returns true if an element has a specified class name
 */
function hasClass(node, className) {
  if (node.className == className) {
    return true;
  }
  var reg = new RegExp('(^| )'+ className +'($| )')
  if (reg.test(node.className)) {
    return true;
  }
  return false;
}

/**
 * Prevents an event from propagating.
 */
function stopEvent(event) {
  if (event.preventDefault) {
    event.preventDefault();
    event.stopPropagation();
  }
  else {
    event.returnValue = false;
    event.cancelBubble = true;
  }
}

/**
 * Removes a class name from an element
 */
function removeNode(node) {
  if (typeof node == 'string') {
    node = document.getElementById(node);
  }
  if (node && node.parentNode) {
    return node.parentNode.removeChild(node);
  }
  else {
    return false;
  }
}

/**
 * main textarea resizer function from drupal
 *
 * you have to define css as following
  .resizable-textarea .grippie {
    height: 14px;
    background: #ECE9D6 url(../to_dir/grippie.png) no-repeat 100% 100%;
    border: 1px solid #DFDBCA;
    border-top-width: 0;
    border-right-width: 0;
    cursor: s-resize;
  }
 */
function textAreaAutoAttach(event, parent) {
  if (typeof parent == 'undefined') {
    // Attach to all visible textareas.
    textareas = document.getElementsByTagName('textarea');
  }
  else {
    // Attach to all visible textareas inside parent.
    textareas = parent.getElementsByTagName('textarea');
  }
  var textarea;
  for (var i = 0; textarea = textareas[i]; ++i) {
    if (hasClass(textarea, 'resizable') && (textarea.nextSibling == null|| !hasClass(textarea.nextSibling, 'grippie'))) {
      if (typeof dimensions(textarea).width != 'undefined' && dimensions(textarea).width != 0) {
        new textArea(textarea);
      }
    }
  }
}

function textArea(element,wrapper) {
  var ta = this;
  this.element = element;
  this.parent = this.element.parentNode;
  this.dimensions = dimensions(element);

  // Prepare wrapper
  if (typeof wrapper=='undefined') {
    this.wrapper = document.createElement('div');
    this.wrapper.className = 'resizable-textarea';
    this.parent.insertBefore(this.wrapper, this.element);
  } else {
    this.wrapper=wrapper;
    this.wrapper.className = 'resizable-textarea';
  }

  // Add grippie and measure it
  this.grippie = document.createElement('div');
  this.grippie.className = 'grippie';
  this.wrapper.appendChild(this.grippie);
  this.grippie.dimensions = dimensions(this.grippie);
  this.grippie.onmousedown = function (e) { ta.beginDrag(e); };

  // Set wrapper and textarea dimensions
  this.wrapper.style.height = this.dimensions.height + this.grippie.dimensions.height + 1 +'px';
  this.element.style.marginBottom = '0px';
  this.element.style.width = '100%';
  this.element.style.height = this.dimensions.height +'px';

  /**
   * textarea_width is defined on (write|edit|reply).php
   */
  textarea_cols(this.element);

  // Wrap textarea
  if (typeof wrapper=='undefined') {
    removeNode(this.element);
    this.wrapper.insertBefore(this.element, this.grippie);
  }

  // Measure difference between desired and actual textarea dimensions to account for padding/borders
  this.widthOffset = dimensions(this.wrapper).width - this.dimensions.width;

  // Make the grippie line up in various browsers
  if (window.opera) {
    // Opera
    this.grippie.style.marginRight = '4px';
  }
  if (document.all && !window.opera) {
    // IE
    this.grippie.style.width = '100%';
    this.grippie.style.paddingLeft = '2px';
  }
  // Mozilla
  this.element.style.MozBoxSizing = 'border-box';

  this.heightOffset = absolutePosition(this.grippie).y - absolutePosition(this.element).y - this.dimensions.height;
}

textArea.prototype.beginDrag = function (event) {
  if (document.isDragging) {
    return;
  }
  document.isDragging = true;

  event = event || window.event;
  // Capture mouse
  var cp = this;
  this.oldMoveHandler = document.onmousemove;
  document.onmousemove = function(e) { cp.handleDrag(e); };
  this.oldUpHandler = document.onmouseup;
  document.onmouseup = function(e) { cp.endDrag(e); };

  // Store drag offset from grippie top
  var pos = absolutePosition(this.grippie);
  this.dragOffset = event.clientY - pos.y;

  // Make transparent
  this.element.style.opacity = 0.4;
  if (window.event) this.element.style.filter = "alpha(opacity=40)";

  // Process
  this.handleDrag(event);
}

textArea.prototype.handleDrag = function (event) {
  event = event || window.event;
  // Get coordinates relative to text area
  var pos = absolutePosition(this.element);
  var y = event.clientY - pos.y;
  var x = event.clientX - pos.x;

  // Set new width
  //var width = Math.max(32, x - this.dragOffset - this.widthOffset) + 3;
  var width = x + 4;
  this.wrapper.style.width = width + 1 + 'px';
  this.element.style.width = width + 'px';

  // Set new height
  var height = Math.max(32, y - this.dragOffset - this.heightOffset);
  this.wrapper.style.height = height + this.grippie.dimensions.height + 1 + 'px';
  this.element.style.height = height + 'px';

  // Avoid text selection
  stopEvent(event);
}

textArea.prototype.endDrag = function (event) {
  // Uncapture mouse
  document.onmousemove = this.oldMoveHandler;
  document.onmouseup = this.oldUpHandler;

  textarea_cols(this.element);

  // Restore opacity
  this.element.style.opacity = 1.0;
  if (window.event) this.element.style.filter = '';
  document.isDragging = false;
}

if (document.jsEnabled) {
  var oldOnload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = textAreaAutoAttach;
  } else {
    window.onload = function() {
      oldOnload();
      textAreaAutoAttach();
    }
  }
  /* addLoadEvent(textAreaAutoAttach); */
}

