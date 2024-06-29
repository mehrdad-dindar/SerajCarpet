<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Carbon Locale(Language)
    |--------------------------------------------------------------------------
    |
    | Option to whether change the language for carbon library or not.
    |
    */
    'carbon' => true,

    /*
    |--------------------------------------------------------------------------
    | Language display name
    |--------------------------------------------------------------------------
    |
    | Option to whether dispaly the language in English or Native.
    |
    */
    'native' => true,

    /*
    |--------------------------------------------------------------------------
    | All Locales (Languages)
    |--------------------------------------------------------------------------
    |
    | Uncomment the languages that your site supports - or add new ones.
    | These are sorted by the native name, which is the order you might show them in a language selector.
    |
    */

    'locales' => [
        'fa'          => ['name' => 'Farsi',                 'script' => 'farsi', 'native' => 'فارسی', 'flag_code' => 'ir'],
        'en'          => ['name' => 'English',                'script' => 'Latn', 'native' => 'English', 'flag_code' => 'us'],
        // 'fr'          => ['name' => 'French',                 'script' => 'Latn', 'native' => 'français', 'flag_code' => 'fr'],

        // 'ace'         => ['name' => 'Achinese',               'script' => 'Latn', 'native' => 'Aceh', 'flag_code' => '' ],
        //'af'          => ['name' => 'Afrikaans',              'script' => 'Latn', 'native' => 'Afrikaans', 'flag_code' => '' ],
        //'agq'         => ['name' => 'Aghem',                  'script' => 'Latn', 'native' => 'Aghem', 'flag_code' => '' ],
        //'ak'          => ['name' => 'Akan',                   'script' => 'Latn', 'native' => 'Akan', 'flag_code' => '' ],
        //'an'          => ['name' => 'Aragonese',              'script' => 'Latn', 'native' => 'aragonés', 'flag_code' => '' ],
        //'cch'         => ['name' => 'Atsam',                  'script' => 'Latn', 'native' => 'Atsam', 'flag_code' => '' ],
        //'gn'          => ['name' => 'Guaraní',                'script' => 'Latn', 'native' => 'Avañe’ẽ', 'flag_code' => '' ],
        //'ae'          => ['name' => 'Avestan',                'script' => 'Latn', 'native' => 'avesta', 'flag_code' => '' ],
        //'ay'          => ['name' => 'Aymara',                 'script' => 'Latn', 'native' => 'aymar aru', 'flag_code' => '' ],
        //'az'          => ['name' => 'Azerbaijani (Latin)',    'script' => 'Latn', 'native' => 'azərbaycanca', 'flag_code' => '' ],
    ]
];
