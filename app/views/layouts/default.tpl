<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>
  {if isset($description)}<meta name="description" content="{$description}"/>{/if}
  {if isset($keywords)}<meta name="keywords" content="{$keywords}" />{/if}
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>{$title}</title>
  {shortcut href="favicon.png"}
  {css href="crust.css"} 
  {js src="jquery.js"}
</head>
<body>
<div id="header">
{img src="crust.png"} <div class="title">Crust Framework</div>
<div style="clear:both;"></div>
</div>
<div id="content">
{$yield}           <br />
{img src="doctrine.png" title="Doctrine ORM"}
{img src="smarty.png" title="Smarty Template Engine"}
{img src="jquery.png" title="jQuery JS Framework"}
<br />
<small>Crust Framework v1.0.0 is based on PHP, uses <i>Doctrine ORM</i>, <i>Smarty Template Engine and includes jQuery. <br />See documentation.pdf for further information, troubleshooting and questions.</i></small>
</div>

</body>
</html>