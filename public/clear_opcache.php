<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared successfully.";
} else {
    echo "OPcache is not enabled or function does not exist.";
}
unlink(__FILE__); // Se auto-elimina por seguridad
?>
