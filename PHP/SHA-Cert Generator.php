<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );
    
    // Create the private and public key
    $res = openssl_pkey_new($config);
    
    // Extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);
    
    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];
    
    file_put_contents('public-key.pem', $pubKey); 
    file_put_contents('private-key.pem', $privKey);

    echo "Zertifikate wurden generiert...";
?>