<?php
function verifyCertificateSignature($certificateFilePath, $signatureFilePath, $publicKeyPath) {
    // Load public key
    if (!file_exists($publicKeyPath)) {
        die("Public key not found at $publicKeyPath");
    }

    $publicKey = file_get_contents($publicKeyPath);
    $pkeyid = openssl_pkey_get_public($publicKey);

    if (!$pkeyid) {
        die("Invalid public key.");
    }

    // Load the original file data
    if (!file_exists($certificateFilePath)) {
        die("Certificate file not found.");
    }

    $data = file_get_contents($certificateFilePath);

    // Load the signature
    if (!file_exists($signatureFilePath)) {
        die("Signature file not found.");
    }

    $signature = file_get_contents($signatureFilePath);

    // Verify the signature
    $ok = openssl_verify($data, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

    if ($ok === 1) {
        echo "✅ Signature is VALID. The file is authentic.\n";
    } elseif ($ok === 0) {
        echo "❌ Signature is INVALID. The file may have been tampered.\n";
    } else {
        echo "⚠️ Error during verification.\n";
    }
}
?>
