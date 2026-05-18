<?php

return [
    'enabled' => env('DEBUGBAR_ENABLED', false),
    'except' => [
        'telescope*',
        'horizon*',
        // Disable untuk semua route PDF
        'admin/laporan/*',
        'admin/siswa-export*',
        'pembayaran/*/receipt',
        'admin/angsuran-du/*',
    ],
];
