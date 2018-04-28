<?php
return array(
    // set your paypal credential
    'client_id' => 'AXLYJgq5galBkHG1M4kIIaU1Cfx7M-geZNxWlPQISCU27uqScoHp6hKRb0JrAzr06Yxf6fQTd_mlBM1p',
    'secret' => 'EOkSKjqx3gfNgjWgeP6Bce06Amm51lXb5kC8G6dCXN7puAYbMrWfPBo_zUOfJt70elqgLTYWV4o5PciA',

    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);