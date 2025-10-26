<?php
    require_once './config.php';

    // Dispatcher for the navbar
    function generate_navbar($role)
    {
        switch($role)
        {
            case 'admin':
                $navbar = file_get_contents(NAVBAR_ADMIN);
                break;
            case 'user':
                $navbar = file_get_contents(NAVBAR_USER);
                break;
            default:
                $navbar = file_get_contents(NAVBAR_GUEST);
        }

        return $navbar;
    }

    // Method to compose the url given the endpoint's name, the port, the protocol and the path
    function compose_url($proto, $socket, $path)
    { return $proto . '://' . $socket . $path; }

    // Method to sent request and receive response from a REST endpoint
    function perform_rest_request($method, $url, $data = null, $token = null) 
    {
        $ch = curl_init();

        // Base headers (always included)
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        // Add Authorization header only if token is provided
        if (!empty($token))
            $headers[] = 'Authorization: ' . $token;
        
        // Configure CURL based on HTTP method
        switch (strtoupper($method)) 
        {
            case 'GET':
                if (!empty($data))
                    $url .= '?' . http_build_query($data);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($data))     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'PUT':
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                if (!empty($data))
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            default:
                throw new Exception("Unsupported HTTP method: $method");
        }

        // Set CURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // disable SSL verification (for local dev)
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // set timeout to 10 seconds

        // Execute request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Handle errors
        if (curl_errno($ch)) 
        {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: $error");
        }

        curl_close($ch);

        // Return decoded response
        return 
        [
            'status' => $http_code,
            'body' => json_decode($response, true)
        ];
    }

    // Method to verify the session is still valid
    // In case is not valid the session is destroyed
    function verify_session()
    {
        session_start();
        if (! isset($_SESSION['session_token']) )
        {
            session_destroy(); // No session already opened
            return false;
        }

        // We have to use the variables in config.php
        $token = $_SESSION['session_token'];
        global $protocol, $socket_account_ms;
        
        $url = compose_url($protocol, $socket_account_ms, '/auth/status');
        $response = perform_rest_request('GET', $url, null, $token);

        // If it is all ok
        if ( $response["body"]["code"] === "0" )
        {
            return true;
        }
        else
        {
            // We have to destroy the session and logout the user
            session_destroy();
            return false;
        }
    }

    // Method to verify the status of a single microservice
    function check_status_microservice($protocol, $socket, $path, $name)
    {
        // Checking account microservice
        $api_url = compose_url($protocol, $socket, $path);
        try
        {
            perform_rest_request('GET', $api_url, null, null);
            $status = [
                'name' => $name,
                'status' => 'active',
                'ip' => explode(":", $socket)[0],
                'port' => explode(":", $socket)[1]
            ];
        }
        catch(Exception $e)
        {
            $status = [
                'name' => $name,
                'status' => 'down',
                'ip' => explode(":", $socket)[0],
                'port' => explode(":", $socket)[1],
                'error'=> 'Timeout reached'
            ];
        }

        return $status;
    }

    // Method to verify the status of all microservices of the system
    // Return an object with all informations
    function check_status_microservices()
    {
        // Importing variables from config.php
        global $protocol, $socket_account_ms, $socket_notification_ms, $socket_park_ms, $socket_payment_ms;
        
        // Resulting array
        $status = array();

        // Checking all microervices
        array_push($status, check_status_microservice($protocol, $socket_account_ms, '/', 'account_ms') );
        array_push($status, check_status_microservice($protocol, $socket_notification_ms, '/', 'notification_ms') );
        array_push($status, check_status_microservice($protocol, $socket_park_ms, '/', 'park_ms') );
        array_push($status, check_status_microservice($protocol, $socket_payment_ms, '/', 'payment_ms') );

        // Returning the resulting array
        return $status;    
    }
?>
