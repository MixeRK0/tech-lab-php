<?php

$adHosts = [];

for ($hostIndex = 1; $hostIndex < 10; $hostIndex++) {
    $host = getenv('AD_HOST_' . $hostIndex) ?: null;

    if (null !== $host) {
        $adHosts[] = $host;
    }
}

return [
    'adminEmail' => 'admin@example.com',
    'user.passwordResetTokenExpire' => 3600,
];
