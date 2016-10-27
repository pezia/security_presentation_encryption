# Example codes to the presentation about cryptography basics

## Installation

    composer install
    
## Running the Examples

    php bin/console

## Examples

### MCrypt

Shows basic examples with MCrypt

* **mcrypt:zero-padding**: mcrypt uses zero bytes for padding
* **mcrypt:ecb**: ECB mode yields the same ciphertext block for the same plaintext block (**do not use it in real life**)
* **mcrypt:cbc-static-iv**: CBC with a static IV degrades yields the same ciphertext for the same plaintext (**do not use it in real life**)
* **mcrypt:cbc-random-iv**: proper usage of CBC with random IV 
* **mcrypt:ofb-static-iv**: OFB with a static IV yields the same keystream (**do not use it in real life**)
* **mcrypt:ofb-random-iv**: proper usage of OFB with random IV
* **mcrypt:ofb-reused-iv**: if OFB is used with a repeating IV, we can calculate the difference of the plaintexts (**do not use it in real life**)

### OpenSSL

* **openssl:padding**: shows the default padding scheme of the OpenSSL module (PKCS#7)
* **openssl:rsa**: shows public-key encryption and digital signature usage with RSA

### HMAC

* **hmac**: shows usage of `hash_hmac($algo, $data, $key, $raw_output = false)` with different hash functions 

### Password Hashing

* **password:md5**: password hashing with MD5 (**do not use it in real life**)
* **password:sha1**: password hashing with SHA1 (**do not use it in real life**)
* **password:sha2**:  password hashing with SHA2 family hash functions
* **password:bcrypt**: password hashing with bcrypt, examples with the `password_*` functions
* **password:string-comparision**: shows how constant time string comparision prohibits timing attacks
