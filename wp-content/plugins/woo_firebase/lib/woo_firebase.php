<?php
//412920-firebase-adminsdk-4w6d7-c1bc514657
use Kreait\Firebase\Factory;
/**
 * get firebase instance
 * @param  [type] $serviceAccount [description]
 * @return instance
 */
function get_firebase() 
{
    return (new Factory)->withServiceAccount(plugin_dir_path(__FILE__) . '/data/expanded_poet.json');
}