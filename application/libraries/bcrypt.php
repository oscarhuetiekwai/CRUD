
<?php 
$bcrypt = new Bcrypt(15);

$hash = $bcrypt->hash('password');
$isGood = $bcrypt->verify('password', $hash);





class Bcrypt {


	private $_ci;


  public function __construct($_ci = 12) {
    if(CRYPT_BLOWFISH != 1) {
      throw new Exception("bcrypt not supported in this installation. See http://php.net/crypt");
    }
	$this->_ci = &get_instance();
    $this->rounds = $_ci;
  }

  public function hash($input) {
    $hash = crypt($input, $this->_ci->getSalt());
    if(strlen($hash) > 13)
      return $hash;

    return false;
  }

  public function verify($input, $existingHash) {
    $hash = crypt($input, $existingHash);

    return $hash === $existingHash;
  }

  private function getSalt() {
    $salt = sprintf('$2a$%02d$', $this->_ci->rounds);

    $bytes = $this->_ci->getRandomBytes(16);

    $salt .= $this->_ci->encodeBytes($bytes);

    return $salt;
  }

  private $randomState;
  private function getRandomBytes($count) {
    $bytes = '';

    if(function_exists('openssl_random_pseudo_bytes') &&
        (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL slow on Win
      $bytes = openssl_random_pseudo_bytes($count);
    }

    if($bytes === '' && is_readable('/dev/urandom') &&
       ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
      $bytes = fread($hRand, $count);
      fclose($hRand);
    }

    if(strlen($bytes) < $count) {
      $bytes = '';

      if($this->_ci->randomState === null) {
        $this->_ci->randomState = microtime();
        if(function_exists('getmypid')) {
          $this->_ci->randomState .= getmypid();
        }
      }

      for($i = 0; $i < $count; $i += 16) {
        $this->_ci->randomState = md5(microtime() . $this->_ci->randomState);

        if (PHP_VERSION >= '5') {
          $bytes .= md5($this->_ci->randomState, true);
        } else {
          $bytes .= pack('H*', md5($this->_ci->randomState));
        }
      }

      $bytes = substr($bytes, 0, $count);
    }

    return $bytes;
  }

  private function encodeBytes($input) {
    // The following is code from the PHP Password Hashing Framework
    $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $output = '';
    $i = 0;
    do {
      $c1 = ord($input[$i++]);
      $output .= $itoa64[$c1 >> 2];
      $c1 = ($c1 & 0x03) << 4;
      if ($i >= 16) {
        $output .= $itoa64[$c1];
        break;
      }

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 4;
      $output .= $itoa64[$c1];
      $c1 = ($c2 & 0x0f) << 2;

      $c2 = ord($input[$i++]);
      $c1 |= $c2 >> 6;
      $output .= $itoa64[$c1];
      $output .= $itoa64[$c2 & 0x3f];
    } while (1);

    return $output;
  }
}
?>