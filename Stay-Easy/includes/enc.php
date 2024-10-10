<?php
    
    // Store a string into the variable which
    // need to be Encrypted
    function encrypt($password){

        $simple_string = $password;
        // $simple_string = "welcome to CJ";
        
        // Display the original string
        // echo "Original String: " . $simple_string;

        // Store the cipher method
        $ciphering = "AES-128-CTR";

        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';
        
        // Store the encryption key
        $encryption_key = "GeeksforGeeks";
        
        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt($simple_string, $ciphering,
                    $encryption_key, $options, $encryption_iv);
        return $encryption;
        // Display the encrypted string
        // echo "<br/>";
        // echo "Encrypted String: " . $encryption . "\n";
    }

    function decrypt($encryption){
        // Store the cipher method
        $ciphering = "AES-128-CTR";
        $options = 0;
        // Non-NULL Initialization Vector for decryption
        $decryption_iv = '1234567891011121';
        
        // Store the decryption key
        $decryption_key = "GeeksforGeeks";
        
        // Use openssl_decrypt() function to decrypt the data
        $decryption=openssl_decrypt ($encryption, $ciphering, 
                $decryption_key, $options, $decryption_iv);
        
        return $decryption;

        // Display the decrypted string
        // echo "<br/>";

        // echo "Decrypted String: " . $decryption;
    }
?>