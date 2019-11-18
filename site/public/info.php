<?php

if ('phpinfo' === $_GET['i']) {
    phpinfo();
}else {
    echo "Php Error on line 0 in c:\wamp\...\info.php?i=%";
}