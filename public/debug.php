<html>
<head>
  <link rel="stylesheet" href="<?php echo URL; ?>public/css/error.css" />
  <script type="text/javascript" language="javascript" src="<?php echo URL; ?>public/js/jquery.js"></script>
</head>
<body>                      <script type="text/javascript" language="javascript" src="<?php echo URL; ?>public/js/jquery.js"></script>     
  <h1>Crust Debug</h1>
  <strong><u>Error:</u></strong> <?php echo $message; ?><br>
  Related parameter is "<?php echo $param; ?>"
  <br><br>Show
  <script type="text/javascript">
    $('#server').hide();
  </script>
  <a href="javascript:void(0);" onclick="$('#server').fadeToggle()" title="Click to list Php Server parameters">SERVER</a> parameters
  <br>
  <div id="server" style="display:none;">
    <table>
      <?php 
      foreach($_SERVER as $x => $y)
      {
        echo '<tr><th>'.$x.'</th><td>'.$y.'</td></tr>';
      }
      ?>
    </table>
  </div>
  <small>Debug pages will not be shown when ENVIRONMENT parameter in app/config.php is set as "production"</small>
</body>
</html>
