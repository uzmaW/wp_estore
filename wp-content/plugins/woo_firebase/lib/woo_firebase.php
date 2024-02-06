<?php
namespace Woo\Firebase\lib;

use MrShan0\PHPFirestore\FirestoreClient;

// Optional, depending on your usage
use MrShan0\PHPFirestore\Fields\FirestoreTimestamp;
use MrShan0\PHPFirestore\Fields\FirestoreArray;



/**
 * get firebase instance
 * @return factory
 */
function get_firebase() 
{
    // Initialize Firebase with the service account credentials
     
     $json = @json_decode(file_get_contents(__DIR__ . '/../data/service-account.json'),true);
      /*  apiKey
        authDomain
        databaseURL
        projectId
        storageBucket
        messagingSenderId
        appId
        measurementId
        */
     
     if(!is_array($json)) return false;
       
     $firestoreClient = new FirestoreClient($json['projectId'], $json['apiKey'], [
        'database' => '(default)',
     ]);
    
     return new class($firestoreClient)  {
        
        private string $collection = 'sys_log';
        private $firestoreClient;

        public function __construct(FirestoreClient $firestoreClient) {
            $this->firestoreClient = $firestoreClient;
        }
        public function getFirestore()
        {
            return $firestoreClient;
        }
        public function getCollection()
        {
            return $this->collection;
        }
        public function addDocument($payload, $collection=null)
        {
            if(!is_null($collection)) $this->collection = $collection;
        
            $data = [
                'payload' => $payload,
                'created_at' => time(),
                'timestamp' => new FirestoreTimestamp,
                'type' => 'log',
                'id'=>uniqid()
            ];
            $this->firestoreClient->addDocument($this->collection, $data);
        
            $data = [
                'payload' => $payload,
                'created_at' => time(),
                'timestamp' => new FirestoreTimestamp,
                'type' => 'log',
                'id'=>uniqid()
            ];
            $this->firestoreClient->addDocument($this->collection, $data);
        } 

    };
}

