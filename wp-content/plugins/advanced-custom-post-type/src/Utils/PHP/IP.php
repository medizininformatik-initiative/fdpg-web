<?php

namespace ACPT\Utils\PHP;

class IP
{
    /**
     * @return string|null
     */
    public static function getClientIP()
    {
    	if(php_sapi_name()){
    		return null;
	    }

	    foreach ( [
		    'HTTP_CLIENT_IP',
		    'HTTP_X_FORWARDED_FOR',
		    'HTTP_X_FORWARDED',
		    'HTTP_X_CLUSTER_CLIENT_IP',
		    'HTTP_FORWARDED_FOR',
		    'HTTP_FORWARDED',
		    'REMOTE_ADDR'
	    ] as $key ) {
		    if ( isset( $_SERVER[ $key ] ) ) {
			    foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
				    if ( filter_var( trim( $ip ), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 ) !== false ) {
					    return $ip;
				    }
			    }
		    }
	    }

        return '127.0.0.1';
    }
}