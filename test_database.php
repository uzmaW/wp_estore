<?php
        try {
		    $dbh = mysqli_init();
		    mysqli_real_connect( $dbh, "localhost", "root", "1234", null, "33060", null, false );
        } catch(\Throwable $e)
        {
           var_export($e->getMessage());
        }
