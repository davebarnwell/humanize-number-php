<?php
/**
 * Humanize numbers
 *
 * @package default
 * @author Dave Barnwell <dave@freshsauce.co.uk>
 */
class HumanizeNumber {
    protected $magnitudes = array(
        12 => 'trillion',
        9  => 'billion',
        6  => 'million',
        3  => 'thousand',
        0  => ''
    );
    protected $abbreviations = array(
        12 => 'T',
        9  => 'B',
        6  => 'M',
        3  => 'K',
        0  => ''
    );
    protected $lowercase_words = array(
        'a','an','and','at','but','by','de','en','for','if','in','of','on','or','the','to','via','vs'
    );

    /**
     * Converts an integer to a string containing commas every three digits.
     *
     * @param $int
     * @return string
     */
    public function intcomma($int) {
        return number_format($int, 0, null, ',');
    }

    /**
     * Setup spacers and 
     *
     * @param bool $compact 
     * @return void
     */
    private function initParams($compact) {
        if($compact) {
            $array = $this->abbreviations;
            $spacer = null;
        } else {
            $array = $this->magnitudes;
            $spacer = ' ';
        }
        return [$array,$spacer];
    }

    /**
     * Converts a large integer to a friendly text representation.
     *
     * @param      $int
     * @param int  $decimal_places
     * @param bool $compact
     * @return string
     */
    public function intword($int, $decimal_places = 0, $compact = false) {
        list($array,$spacer) = $this->initParams($compact);
        foreach($array as $exponent => $suffix)
        {
            if($int >= pow(10, $exponent))
            {
                return round(floatval($int / pow(10, $exponent)), $decimal_places) . $spacer . $suffix;
            }
        }
        return $int;
    }
    
    /**
     * Converts a large integer to a friendly text representation, when it's longer than $shorten_when_longer chars
     *
     * @param      $int
     * @param int  $decimal_places when shortend
     * @param int  $shorten_when_longer
     * @param bool $compact
     * @return string
     */
    public function intwordover($int, $decimal_places = 0, $shorten_when_longer = 5, $compact = false) {
        list($array,$spacer) = $this->initParams($compact);
        if (strlen(number_format($int,0)) <= $shorten_when_longer) {
          return number_format($int);
        }
        foreach($array as $exponent => $suffix)
        {
            if($int >= pow(10, $exponent))
            {
                return round(floatval($int / pow(10, $exponent)), $decimal_places) . $spacer . $suffix;
            }
        }
        return $int;
    }

    /**
     * Return AP formatted numbers, use words for numbers less than 10.
     *
     * @param $int
     * @return mixed
     */
    public function apnumber($int) {
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
        );
        return isset($dictionary[$int]) ? $dictionary[$int] : $int;
    }

    /**
     * Converts an integer to its ordinal as a string.
     *
     * @param $number
     * @return string
     */
    public function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        $mod100 = $number % 100;
        return $number . ($mod100 >= 11 && $mod100 <= 13 ? 'th' :  $ends[$number % 10]);
    }

    /**
     * Formats a number to a human-readable number.
     *
     * @param $number
     * @return string
     */
    public function formatnumber($number) {
        return number_format($number, 2, '.', ',');
    }

    /**
     * Converts an integer into a compact representation.
     *
     * @param     $int
     * @param int $shorten_when_longer if the int with commas is longer than this shorten
     * @param int $decimal_places
     * @return string
     */
    public function compactinteger($int, $decimal_places = 0, $shorten_when_longer = 5) {
        return $this->intwordover($int, $decimal_places, $shorten_when_longer, true);
    }

    /**
     * Bounds a value from above.
     *
     * @param $value
     * @param $max
     * @return string
     */
    public function boundednumber($value, $max) {
        if($value > $max) return $max . '+';
        return $value;
    }

    /**
     * Interprets numbers as occurrences. Also accepts an optional array/map of overrides.
     *
     * @param       $value
     * @param array $overrides
     * @return string
     */
    public function times($value, $overrides = []) {
        $return_value = isset($overrides[$value]) ? $overrides[$value] : $value;
        switch($value) {
            case 0:
                return 'never';
            case 1:
                return 'once';
            case 2:
                return 'twice';
            default:
                return $return_value . ' times';
        }
    }

    /**
     * Formats the value like a 'human-readable' file size (i.e. '13 KB', '4.1 MB', '102 bytes', etc).
     *
     * @param     $bytes
     * @param int $decimal_places
     * @param int $bytes_in_kb
     * @return string
     */
    public function filesize($bytes, $decimal_places = 0, $bytes_in_kb = 1024) {
        $bytes_in_tb = $bytes_in_kb * $bytes_in_kb * $bytes_in_kb * $bytes_in_kb;
        $bytes_in_gb = $bytes_in_kb * $bytes_in_kb * $bytes_in_kb;
        $bytes_in_mb = $bytes_in_kb * $bytes_in_kb;
        if ($bytes >= $bytes_in_tb)
        {
            $bytes = number_format($bytes / $bytes_in_tb, $decimal_places) . ' TB';
        }
        elseif ($bytes >= $bytes_in_gb)
        {
            $bytes = number_format($bytes / $bytes_in_gb, $decimal_places) . ' GB';
        }
        elseif ($bytes >= $bytes_in_mb)
        {
            $bytes = number_format($bytes / $bytes_in_mb, $decimal_places) . ' MB';
        }
        elseif ($bytes >= $bytes_in_kb)
        {
            $bytes = number_format($bytes / $bytes_in_kb, $decimal_places) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
}
