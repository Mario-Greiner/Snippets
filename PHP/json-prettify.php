<?php
    $minJson = json_decode('{"key":"value","key2":"value2"}');
    
    echo '<pre>';
    echo json_encode($minJson, JSON_PRETTY_PRINT);
    echo '</pre>';
?>
