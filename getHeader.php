<?php
error_reporting( E_ERROR );

$argv    = $_SERVER[ 'argv' ];
$method1 = array_search( "-M", $argv );
$method2 = array_search( "--method", $argv );
$file1   = array_search( "-S", $argv );
$file2   = array_search( "--site-list", $argv );
$path1   = array_search( "-P", $argv );
$path2   = array_search( "--path-list", $argv );
$help1   = array_search( "-H", $argv );
$help2   = array_search( "--help", $argv );

if( $argv < 1 ) {
    echo "Use the '-H' or '--help' for the information about this tools.";
    exit;
}

if( !empty( $help1 ) || !empty( $help2 ) ) {
    echo "This script was created by Yus.\nwa: +62 896-3621-6331\nFb: yus.127.0.0.1\n\n";
    echo "-H, --help => Check the information about the options for this tools.\n";
    echo "-M, --method => The option for checking method ( Curl : [curl, C, 1], Wget : [wget, W, 2], GetHeader : [getheader, G, 3], PHP Curl : [phpcurl, P, 4].\n";
    echo "-S, --site-list => The option for the list site.\n";
    echo "-P, --path-list => The option for the list path.\n"; 
    echo "\nExample: \n";
    echo "php getHeader.php -M curl -S site.txt -P path.txt\n";
    exit;
}

if( empty( $file1 ) && empty( $file2 ) ) {
    echo "Site list's arguments should be used!\n";
    exit;
}
else if( !empty( $file1 ) ) {
    if( empty( $argv[ $file1 + 1 ] ) ) {
        echo "Site list's file name can't identified\n";
        exit;
    } else {        
        if( file_exists( $argv[ $file1 + 1 ] ) ) {
            $file     = file_get_contents( $argv[ $file1 + 1 ] );
            $listsite = explode( "\r\n", $file );

            echo "Your site list's file name is: " . $argv[ $file1 + 1 ] . "\n";
        } else {
            echo "Your site list's file name doesn't exist.\n";
            exit;
        }
    }
}
else {
    if( empty( $argv[ $file2 + 1 ] ) ) {
        echo "Site list's file name can't identified\n";
        exit;
    } else {        
        if( file_exists( $argv[ $file2 + 1 ] ) ) {
            $file     = file_get_contents( $argv[ $file2 + 1 ] );
            $listsite = explode( "\r\n", $file );

            echo "Your site list's file name is: " . $argv[ $file2 + 1 ] . "\n";
        } else {
            echo "Your site list's file name doesn't exist.\n";
        }
    }
}

if( empty( $path1 ) && empty( $path2 ) ) {
    echo "Path list's arguments should be used!\n";
    exit;
}
else if( !empty( $path1 ) ) {
    if( empty( $argv[ $path1 + 1 ] ) ) {
        echo "Path list's file name can't identified\n";
        exit;
    } else {        
        if( file_exists( $argv[ $path1 + 1 ] ) ) {
            $filepath = file_get_contents( $argv[ $path1 + 1 ] );
            $listpath = explode( "\r\n", $filepath );

            echo "Your path list's file name is: " . $argv[ $path1 + 1 ] . "\n";
        } else {
            echo "Your path list's file name doesn't exist.\n";
            exit;
        }
    }
}
else {
    if( empty( $argv[ $path2 + 1 ] ) ) {
        echo "Path list's file name can't identified\n";
        exit;
    } else {        
        if( file_exists( $argv[ $path2 + 1 ] ) ) {
            $filepath = file_get_contents( $argv[ $path2 + 1 ] );
            $listpath = explode( "\r\n", $filepath );

            echo "Your site list's file name is: " . $argv[ $path2 + 1 ] . "\n";
        } else {
            echo "Your site list's file name doesn't exist.\n";
        }
    }
}

if( empty( $method1 ) && empty( $method2 ) ) {
    echo "Method arguments should be used!\n";
    exit;
}
else if( !empty( $method1 ) ) {
    if( $argv[ $method1 + 1 ] == "curl" || $argv[ $method1 + 1 ] == "C" || $argv[ $method1 + 1 ] == "1" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls  = "http://" . $ls;
                }
                else {
                    $ls = $ls;
                }
    
                echo "Site: " . $ls . "/" . $lp . " get status code => ";
                system( "curl -s -LI " . $ls . "/" . $lp . " -o null -w \"%{http_code}\n\"" );
                echo "\n";

                $code = file_get_contents( "null" );

                if( $code == 301 ){
                    echo "Checking with https protocol..\n";
                    echo "Site: " . $ls2 . "/" . $lp . " get status code => ";
                    system( "curl -s -LI " . $ls2 . "/" . $lp . " -o null -w \"%{http_code}\n\"" );
                    echo "\n";
                }

                echo "\n";

                unlink( "null" );
            }
        }
    }
    else if( $argv[ $method1 + 1 ] == "wget" || $argv[ $method1 + 1 ] == "W" || $argv[ $method1 + 1 ] == "2" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                    
                }
                else {
                    $ls = $ls;
                }
    
                system( "wget -S " . $ls . "/" . $lp . " > wget.txt" );
    
                $feeling = file_get_contents( "wget.txt" );
                $explode = explode( "\n", $feeling );
    
                echo "Site: " . $ls . "/" . $lp . " get status code => " . $explode[ 0 ] . "\n";

                if( $explode[ 0 ] == 301 ) {
                    echo "Checking with https protocol..\n";
                    system( "wget -S " . $ls2 . "/" . $lp . " > wget2.txt" );
    
                    $feeling = file_get_contents( "wget2.txt" );
                    $explode = explode( "\n", $feeling );
        
                    echo "Site: " . $ls2 . "/" . $lp . " get status code => " . $explode[ 0 ] . "\n";
                    unlink( "wget2.txt" );
                }
                
                echo "\n";
                unlink( "wget.txt" );
            }
        }
    }
    else if( $argv[ $method1 + 1 ] == "getheader" || $argv[ $method1 + 1 ] == "G" || $argv[ $method1 + 1 ] == "3" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                }
                else {
                    $ls = $ls;
                }
                
                stream_context_set_default( [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ] );
                
                $code = get_headers( $ls . "/" . $lp, 1 )[ 0 ];
                $rege = explode( " ", $code );
                $rege = $rege[ 1 ];

                echo "Site: " . $ls . "/" . $lp . " get status code =>" . $code . "\n";

                if( $rege == 301 ) {
                    stream_context_set_default( [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ] );

                    echo "Checking with https protocol..\n";
                    echo "Site: " . $ls2 . "/" . $lp . " get status code =>" . get_headers( $ls2 . "/" . $lp, 1 )[ 0 ] . "\n";
                }
                else {
                    echo "Site: " . $ls2 . "/" . $lp . " maybe get down.\n";
                }

                echo "\n";
            }
        }
    }
    else if( $argv[ $method1 + 1 ] == "phpcurl" || $argv[ $method1 + 1 ] == "P" || $argv[ $method1 + 1 ] == "4" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                }
                else {
                    $ls = $ls;
                }
    
                $url      = $ls . "/" . $lp;
                $ch       = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_HEADER, true );
                curl_setopt( $ch, CURLOPT_NOBODY, true );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                curl_exec( $ch );
                $response = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                curl_close( $ch );
    
                echo "Site: " . $ls . "/" . $lp . " get status code =>" . $response . "\n";

                if( $response == 301 ) {
                    echo "Checking with https protocol..\n";
                    $url      = $ls2 . "/" . $lp;
                    $ch       = curl_init();
                    curl_setopt( $ch, CURLOPT_URL, $url );
                    curl_setopt( $ch, CURLOPT_HEADER, true );
                    curl_setopt( $ch, CURLOPT_NOBODY, true );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                    curl_exec( $ch );
                    $response = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                    curl_close( $ch );
        
                    echo "Site: " . $ls2 . "/" . $lp . " get status code =>" . $response . "\n";
                }

                echo "\n";
            }
        }
    }
    else {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                }
                else {
                    $ls = $ls;
                }
        
                $code = get_headers( $ls . "/" . $lp, 1 )[ 0 ];

                echo "Site: " . $ls . "/" . $lp . " get status code =>" . $code . "\n";

                if( $code == 301 ) {
                    echo "Checking with https protocol..\n";
                    echo "Site: " . $ls2 . "/" . $lp . " get status code =>" . get_headers( $ls2 . "/" . $lp, 1 )[ 0 ] . "\n";
                }
                
                echo "\n";
            }
        }
    }
    exit;
}
else {
    if( $argv[ $method2 + 1 ] == "curl" || $argv[ $method2 + 1 ] == "C" || $argv[ $method2 + 1 ] == "1" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                }
                else {
                    $ls = $ls;
                }
    
                echo "Site: " . $ls . "/" . $lp . " get status code => ";
                system( "curl -s -LI " . $ls . "/" . $lp . " -o null -w \"%{http_code}\n\"" );
                echo "\n";

                if( $code == 301 ){
                    echo "Checking with https protocol..\n";
                    echo "Site: " . $ls2 . "/" . $lp . " get status code => ";
                    system( "curl -s -LI " . $ls2 . "/" . $lp . " -o null -w \"%{http_code}\n\"" );
                    echo "\n";
                }

                echo "\n";

                unlink( "null" );
            }
        }
    }
    else if( $argv[ $method2 + 1 ] == "wget" || $argv[ $method2 + 1 ] == "W" || $argv[ $method2 + 1 ] == "2" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                }
                else {
                    $ls = $ls;
                }
    
                system( "wget -S " . $ls . "/" . $lp . " > wget.txt" );
    
                $feeling = file_get_contents( "wget.txt" );
                $explode = explode( "\n", $feeling );
    
                echo "Site: " . $ls . "/" . $lp . " get status code => " . $explode[ 0 ] . "\n";

                if( $explode[ 0 ] == 301 ) {
                    echo "Checking with https protocol..\n";
                    system( "wget -S " . $ls2 . "/" . $lp . " > wget2.txt" );
    
                    $feeling = file_get_contents( "wget2.txt" );
                    $explode = explode( "\n", $feeling );
        
                    echo "Site: " . $ls2 . "/" . $lp . " get status code => " . $explode[ 0 ] . "\n";
                    unlink( "wget2.txt" );
                }

                echo "\n";

                unlink( "wget.txt" );
            }
        }
    }
    else if( $argv[ $method2 + 1 ] == "getheader" || $argv[ $method2 + 1 ] == "G" || $argv[ $method2 + 1 ] == "3" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                    
                }
                else {
                    $ls = $ls;
                }
                stream_context_set_default( [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ] );

                $code = get_headers( $ls . "/" . $lp, 1 )[ 0 ];
                $rege = explode( " ", $code );
                $rege = $rege[ 1 ];

                echo "Site: " . $ls . "/" . $lp . " get status code =>" . $code . "\n";

                if( $rege == 301 ) {
                    stream_context_set_default( [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ] );
                    
                    echo "Checking with https protocol..\n";
                    echo "Site: " . $ls2 . "/" . $lp . " get status code =>" . get_headers( $ls2 . "/" . $lp, 1 )[ 0 ] . "\n";
                }
                else {
                    echo "Site: " . $ls2 . "/" . $lp . " maybe get down.\n";
                }

                echo "\n";
            }
        }
    }
    else if( $argv[ $method2 + 1 ] == "phpcurl" || $argv[ $method2 + 1 ] == "P" || $argv[ $method2 + 1 ] == "4" ) {
        foreach( $listpath as $lp ) {
            echo "\nPath currently checking: " . $lp . "\n";

            foreach( $listsite as $ls ) {
                preg_match("/http/", $ls, $protocol);
    
                if( empty( $protocol ) ) {
                    $ls2 = "https://" . $ls;
                    $ls = "http://" . $ls;
                }
                else {
                    $ls = $ls;
                }
    
                $url      = $ls . "/" . $lp;
                $ch       = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_HEADER, true );
                curl_setopt( $ch, CURLOPT_NOBODY, true );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                curl_exec( $ch );
                $response = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                curl_close( $ch );
    
                echo "Site: " . $ls . "/" . $lp . " get status code =>" . $response  . "\n";

                if( $response == 301 ) {
                    echo "Checking with https protocol..\n";
                    $url      = $ls2 . "/" . $lp;
                    $ch       = curl_init();
                    curl_setopt( $ch, CURLOPT_URL, $url );
                    curl_setopt( $ch, CURLOPT_HEADER, true );
                    curl_setopt( $ch, CURLOPT_NOBODY, true );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                    curl_exec( $ch );
                    $response = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
                    curl_close( $ch );
        
                    echo "Site: " . $ls2 . "/" . $lp . " get status code =>" . $response . "\n";
                }
                
                echo "\n";
            }
        }
    }
}
