<?php
return array(
    // set your paypal credential
    'client_id' => 'ASKfQu6jrUO2JFvkIY7A5gBpPvCBIko1znBsvfzJ47BVXXG8tpRNNPwUZYI6BgSD6_Tz7e2zT25RcItn',
    'secret' => 'EBQbFH36UCsKKC7Wdx-RDG7qNU9NXKkEV5JcJnFyByOvKFFWergMHZWThCATaq-Pxe8okmU0SSDIdvS4',
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