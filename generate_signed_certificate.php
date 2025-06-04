<?php
function signCertificate($studentURN, $outputFilePath) {
    $originalFile = 'No-Dues-Certificate-Format.png';  // Or PDF
    $privateKeyPath = 'keys/private.pem'; // Store securely
    $signatureFile = $outputFilePath . '.sig';

    // Load private key
    $privateKey = file_get_contents($privateKeyPath);
    $pkeyid = openssl_pkey_get_private($privateKey);

    // Hash the file (you could use other data too)
    $data = file_get_contents($originalFile);
    openssl_sign($data, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

    // Save the signature and the original file copy
    file_put_contents($signatureFile, $signature);
    copy($originalFile, $outputFilePath); // Optional: overlay student info if desired

    // Return path of signed file
    return $outputFilePath;
}
?>
