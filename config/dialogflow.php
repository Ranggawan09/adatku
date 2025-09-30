<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Dialogflow ES Configuration
    |--------------------------------------------------------------------------
    */

    'project_id' => env('DIALOGFLOW_PROJECT_ID'),

    // Menggunakan storage_path() untuk path yang lebih andal.
    // Pastikan file kredensial ada di dalam folder storage/app/
    'credentials' => storage_path('app/' . env('DIALOGFLOW_CREDENTIALS_FILENAME', 'dialogflow-credentials.json')),

];