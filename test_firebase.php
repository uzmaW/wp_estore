<?php
include 'wp-load.php';
include __DIR__.'/wp-content/plugins/woo_firebase/vendor/autoload.php';


        try {
            $fbh = \Woo\Firebase\lib\get_firebase();
            $data = [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
            ];
            
            // Add a new document with a unique ID
            $fbh->addDocument($data);
            
            // Or, set data to a specific document (if you know the document ID)
            // $database->collection('users')->document('user_id')->set($data);
        } catch(\Throwable $e)
        {
           var_export($e->getMessage());
        }
