<?php
function supabase_request($method, $url, $api_key, $data=null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        "Content-Type: application/json",
        "apikey: $api_key",
        "Authorization: Bearer $api_key"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    $result = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code'=>$code, 'body'=>$result];
}

function send_verification_email($to, $code, $from_email) {
    $subject = 'Votre code de validation Codix';
    $message = "Bonjour,\n\nVotre code de vérification Codix : $code\n\nMerci,\nCodix";
    $headers = 'From: ' . $from_email . "\r\n" . 'Reply-To: ' . $from_email . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    return mail($to, $subject, $message, $headers);
}
?>