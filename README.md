# Example codes to the presentation about cryptography basics

## Installation

    composer install
    
## Running the Examples

    php bin/console

## Examples

### MCrypt

Shows basic examples with MCrypt

* **mcrypt:zero-padding**: mcrypt uses zero bytes for padding
* **mcrypt:ecb**: ECB mode yields the same ciphertext block for the same plaintext block
* **mcrypt:cbc-static-iv**: CBC with a static IV degrades yields the same ciphertext for the same plaintext 
* **mcrypt:cbc-random-iv**: proper usage of CBC with random IV 
* **mcrypt:ofb-static-iv**: OFB with a static IV yields the same keystream 
* **mcrypt:ofb-random-iv**: proper usage of OFB with random IV
* **mcrypt:ofb-reused-iv**: if OFB is used with a repeating IV, we can calculate the difference of the plaintexts

### OpenSSL

### HMAC

* **hmac**: shows usage of `hash_hmac($algo, $data, $key, $raw_output = false)` with different hash functions 

### Password Hashing

* **password:md5**
* **password:sha1**
* **password:sha2**
* **password:bcrypt**: password hashing with bcrypt, examples with the `password_*` functions
* **password:string-comparision**: shows how constant time string comparision prohibits timing attacks
