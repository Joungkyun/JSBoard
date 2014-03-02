<?php
#
# minimal wikify module for jsboard by wkpark@kldp.org
# $Id: wikify.php,v 1.4 2014-03-02 17:11:32 oops Exp $
#

$_config=array();
$_config['intermap']='config/intermap.txt';
$_config['sistermap']='config/sistermap.txt';
$_config['shared_intermap']='';
$_config['query_prefix']='/';
$_config['imgs_dir_interwiki']='images/interwiki/';

// {{{ +-- public get_scriptname(void)
function get_scriptname() {
  // Return full URL of current page.
  // $_SERVER["SCRIPT_NAME"] has bad value under CGI mode
  // set 'cgi.fix_pathinfo=1' in the php.ini under
  // apache 2.0.x + php4.2.x Win32
  return $_SERVER["SCRIPT_NAME"];
}
// }}}

// {{{ +-- public qualifiedUrl($url)
function qualifiedUrl($url) {
  if (substr($url,0,7)=="http://")
    return $url;
  return "http://$_SERVER[HTTP_HOST]$url";
}
// }}}

// {{{ +-- public _urlencode($url)
function _urlencode($url) {
  return preg_replace("/([^a-z0-9\/\?\.\+~#&:;=%\-_]{1})/ie","'%'.strtoupper(dechex(ord(substr('\\1',-1))))",$url);
}
// }}}

// {{{ +-- public macro_interwiki($options=array(void))
function macro_interwiki($options=array()) {
  global $_config;

  if (true or $options['init']) {
    $interwiki=array();
    $intericon=array();
    # intitialize interwiki map

    if(!file_exists($_config['intermap']))
      return;

    $map=file($_config['intermap']);
    if ($_config['sistermap'] and file_exists($_config['sistermap']))
      $map=array_merge($map,file($_config['sistermap']));

    # read shared intermap
    if (file_exists($_config['shared_intermap']))
      $map=array_merge($map,file($_config['shared_intermap']));

    for ($i=0;$i<sizeof($map);$i++) {
      $line=rtrim($map[$i]);
      if (!$line || $line[0]=="#" || $line[0]==" ") continue;
      if (preg_match("/^[A-Z]+/",$line)) {
        $wiki=strtok($line,' ');$url=strtok(' ');
        $dumm=trim(strtok(''));
        if (preg_match('/^(http|ftp):/',$dumm,$match)) {
          $icon=strtok($dumm,' ');
          preg_match('/^(\d+)(x(\d+))?\b/',strtok(''),$sz);
          $sx=$sz[1];$sy=$sz[3];
          $sx=$sx ? $sx:16; $sy=$sy ? $sy:16;
          $intericon[$wiki]=array($sx,$sy,trim($icon));
        }
        $interwiki[$wiki]=trim($url);
        $interwikirule.="$wiki|";
      }
    }
    $interwikirule.="Self";
    $interwiki['Self']=get_scriptname().$_config['query_prefix'];

    # read shared intericons
    $map=array();
    if (file_exists($_config['shared_intericon']))
      $map=array_merge($map,file($_config['shared_intericon']));

    for ($i=0;$i<sizeof($map);$i++) {
      $line=rtrim($map[$i]);
      if (!$line || $line[0]=="#" || $line[0]==" ") continue;
      if (preg_match("/^[A-Z]+/",$line)) {
        $wiki=strtok($line,' ');$icon=trim(strtok(' '));
        if (!preg_match('/^(http|ftp):/',$icon,$match)) continue;
        preg_match('/^(\d+)(x(\d+))?\b/',strtok(''),$sz);
        $sx=$sz[1];$sy=$sz[3];
        $sx=$sx ? $sx:16; $sy=$sy ? $sy:16;
        $intericon[$wiki]=array($sx,$sy,trim($icon));
      }
    }
    $_config['interwiki']=$interwiki;
    $_config['interwikirule']=$interwikirule;
    $_config['intericon']=$intericon;
  }
  if ($options['init']) return;
}
// }}}

// {{{ +-- public interwiki_repl($url,$text='',$attr='',$extra='')
function interwiki_repl($url,$text='',$attr='',$extra='') {
  global $_config;

  if ($url[0]=="w")
    $url=substr($url,5);
  $dum=explode(":",$url,2);
  $wiki=$dum[0]; $page=$dum[1];

  if (sizeof($dum) == 1) {
    # wiki:FrontPage(not supported in the MoinMoin
    # or [wiki:FrontPage Home Page]
    $page=$dum[0];
    if (!$text)
      return word_repl($page,$page.$extra,$attr,1);
    return word_repl($page,$text.$extra,$attr,1);
  }

  $url=$_config['interwiki'][$wiki];
  # invalid InterWiki name
  if (!$url) {
     return $dum[0].':'.($dum[1]?$dum[1]:'');
#    $dum0=preg_replace("/(".$_config['wordrule'].")/e","link_repl('\\1')",$dum[0]);
#    return $dum0.':'.($dum[1]?word_repl($dum[1],$text):'');
  }
  if ($page=='/') $page='';
  $urlpage=_urlencode(trim($page));
  #$urlpage=trim($page);
  if (strpos($url,'$PAGE') === false)
    $url.=$urlpage;
  else {
    # GtkRef http://developer.gnome.org/doc/API/2.0/gtk/$PAGE.html
    # GtkRef:GtkTreeView#GtkTreeView
    # is rendered as http://...GtkTreeView.html#GtkTreeView
    $page_only=strtok($urlpage,'#?');
    $query= substr($urlpage,strlen($page_only));
    #if ($query and !$text) $text=strtok($page,'#?');
    $url=str_replace('$PAGE',$page_only,$url).$query;
  }

  $icon=$_config['imgs_dir_interwiki'].strtolower($wiki).'-16.png';
  $sx=16;$sy=16;
  if ($_config['intericon'][$wiki]) {
    $icon=$_config['intericon'][$wiki][2];
    $sx=$_config['intericon'][$wiki][0];
    $sy=$_config['intericon'][$wiki][1];
  }

  $img="<a href='$url' target='wiki'>".
       "<img border='0' src='$icon' align='middle' height='$sy' ".
       "width='$sx' alt='$wiki:' title='$wiki:' /></a>";
  #if (!$text) $text=str_replace("%20"," ",$page);
  if (!$text) $text=urldecode($page);
  else if (preg_match("/^(http|ftp|attachment):.*\.(png|gif|jpeg|jpg)$/i",$text)) {
    if (substr($text,0,11)=='attachment:') {
      $fname=substr($text,11);
      $ntext=macro_repl('Attachment',$fname,1);
      if (!file_exists($ntext))
        $text=macro_repl('Attachment',$fname);
      else {
        $text=qualifiedUrl($_config['url_prefix'].'/'.$ntext);
        $text= "<img border='0' alt='$text' src='$text' />";
      }
    } else
      $text= "<img border='0' alt='$text' src='$text' />";
    $img='';
  }

  if (preg_match("/\.(png|gif|jpeg|jpg)$/i",$url))
    return "<a href='".$url."' $attr title='$wiki:$page'><img border='0' align='middle' alt='$text' src='$url' /></a>$extra";

  return $img. "<a href='".$url."' $attr title='$wiki:$page'>$text</a>$extra";
}
// }}}

// {{{ +-- public link_repl($url,$attr='')
function link_repl($url,$attr='') {
  #if ($url[0]=='<') { print $url;return $url;}
  $url=str_replace('\"','"',$url);
  if ($url[0]=="[") {
    $url=substr($url,1,-1);
    $force=1;
  }
#
  switch ($url[0]) {
  case '{':
    $url=substr($url,3,-3);
    if ($url[0]=='#' and ($p=strpos($url,' '))) {
      $col=strtok($url,' '); $url=strtok('');
      if (!preg_match('/^#[0-9a-f]{6}$/',$col)) $col=substr($col,1);
      return "<font color='$col'>$url</font>";
    } else if (preg_match('/^((?:\+|\-)([1-6]?))(?=\s)(.*)$/',$url,$m)) {
      if ($m[2]=='') $m[1].='1';
      return "<font size='$m[1]'>$m[3]</font>";
    }
    return "<tt class='wiki'>$url</tt>"; # No link
    break;
  }
  if (strpos($url,":")) {

    if ($url[0] == '^') {
      $attr.=' target="_blank" ';
      $url=substr($url,1);
      $external_icon=$_config['icon']['external'];
    }

    if (preg_match("/^(w|[A-Z])/",$url)) { # InterWiki or wiki:
      if (strpos($url," ")) { # have a space ?
        $dum=explode(" ",$url,2);
        return interwiki_repl($dum[0],$dum[1],$attr,$external_icon);
      }

      return interwiki_repl($url,'',$attr,$external_icon);
    }
  }
  return $url;
}
// }}}

// {{{ +-- public wikify(&$line,$options=array(void))
function wikify(&$line,$options=array()) {
  global $_config;

  if(!file_exists($_config['intermap']))
    return;

  $_config['wordrule'] ="({{{(?U)(.+)}}})|";
  $_config['wordrule'].="(\b|\^?)([A-Z][a-zA-Z]+):([^\(\)<>\s\']*[^\(\)<>\s\'\",\.:\?\!]*(\s(?![\x33-\x7e]))?)";

  $_config['baserule']="''''''";
  $_config['baserepl']="<b></b>";
  $line=preg_replace("/(".$_config['baserule'].")/",$_config['baserepl'],$line);

  $line=preg_replace("/(".$_config['wordrule'].")/e","link_repl('\\1')",$line);
}
// }}}

# test
#macro_interwiki();
#
#$line='hello Wiki:FrontPage gello';
#wikify($line);
#
#echo $line;

/*
 * Local variables:
 * tab-width: 2
 * indent-tabs-mode: nil
 * c-basic-offset: 2
 * show-paren-mode: t
 * End:
 * vim600: filetype=php et ts=2 sw=2 fdm=marker
 * vim<600: filetype=php et ts=2 sw=2
 */
?>
