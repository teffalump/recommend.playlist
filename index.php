<?php

/* This will display a library, if one is specified, or else a login box. The box will accept a passphrase which will then fetch the associated music library */

if (isset($_GET['lib'])) 
{
    /* Retrieve the library */
}
else
{
    /* Give a passphrase box */
    echo '<html><head>txt2rec</head><body><form method="get" action="display.php">Passphrase: <input type="text" name="lib" /><button>Get Library</button></form></body></html>';
}
?>
