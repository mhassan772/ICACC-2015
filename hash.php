<?php




#these variables are from the php page
##################################################
$key_string = "hhhzvnrs3k3md2xsr2htttmy20r1joxjaafyl4nkwshatjqbr9z0z5ok79hy34dk";
$orig_pass = password_hash(hash_hmac("sha256", base64_encode(str_repeat('flagstones', 3)), $key_string, true),PASSWORD_BCRYPT);
##################################################



#this function is to convert from String to Hex
#source of the function: http://stackoverflow.com/questions/14674834/php-convert-string-to-hex-and-hex-to-string

function strToHex($string){
	$hex = '';
	for ($i=0; $i<strlen($string); $i++){
		$ord = ord($string[$i]);
		$hexCode = dechex($ord);
		$hex .= substr('0'.$hexCode, -2);
    	}
    return strToUpper($hex);
}


echo strToHex(hash_hmac("sha256", base64_encode(str_repeat('flagstones', 3)), $key_string, true));

# the program start from here
echo "the program is anayzing the keys..\n";


#opening the file that have the wordlists list in your device
#AllWordListInKali file is generated by the command: `locate wordlist | grep -v '.gz' | grep '.txt\|.lst' > AllWordListInKali `

$handleWL = fopen("/root/Downloads/AllWordListInKali","r");
if ($handleWL) {
	while (($wordlist = fgets($handleWL)) !== false) {
#opening the wordlist files one by one..
		$handle = fopen(trim($wordlist),"r");
		echo "Analyzing the wordlist: $wordlist \n";
		if ($handle) {
    			while (($theword = fgets($handle)) !== false) {
#process the wordlist word by word..

#remove the newline char from the word	
				$theword = trim($theword);
#calculate the hash. This function is taken from the php page
				$hash = hash_hmac("sha256", base64_encode(str_repeat($theword, 3)), $key_string, true);
#convert the hash to hex	
				$hash_hex = strToHex($hash);
#substing the first 6 chars
				$first6 = substr ( $hash_hex , 0, 6 );
#testing the if the first 6 chars from the hash is equal to 'C5CE00'
				if($first6 == 'C5CE00'){
					echo "the word is in plaintext: \n";
					echo $theword;
					echo "\n";
					echo "the sub is: \n";
					echo $first6;
					echo "\n";
					echo "the whole hash is: \n";
					echo $hash_hex;
					echo "\n";
					echo "the file list is: $wordlist";
					
					exit(0);
				}

    			}

    		fclose($handle);
		} 
		else {
     			echo "error opening the file $wordlist";
		}

	}

    fclose($handleWL);


}else {
    echo "error opening the file AllWordListInKali";
}


?>
