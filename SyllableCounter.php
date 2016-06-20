<?php
class SyllableCounter {
    private $debugMode = false;
    function __construct($_debug = false) {
        $this->debugMode = $_debug;
    }
    public function count($sentence) {
        $words = preg_split('/ |\s|,|\./', $sentence);
        $c = 0;
        foreach ($words as $word) {
            $n = $this->countWord($word);
            $this->debugLog("countSyllablesInWord : ".$word." (".$n.").");
            $c += $n;
        }
        return $c;
    }
    private function countWord($word) {
        $this->debugLog("countSyllablesInWord : ".$word);
        $word = strtolower($word);

        //例外定義
        $exception_add = ['serious','crucial'];
        $exception_del = ['fortunately','unfortunately'];
        $co_one = ['cool','coach','coat','coal','count','could','coin','coarse','coup','coif','cook','coign','coiffe','coof','court'];
        $co_two = ['coapt','coed','coinci'];
        $pre_one = ['preach'];

        $syls = 0; #added syllable number
        $disc = 0; #discarded syllable number
        $numVowels = 0;

        // #1) if letters < 3 : return 1
        if(strlen($word) <= 3){
            $syls = 1;
            return $syls;
        }
        $this->debugLog("#01 : ".$numVowels." / ".$syls." / ".$disc);

        // #2) if doesn't end with "ted" or "tes" or "ses" or "ied" or "ies", discard "es" and "ed" at the end.
        // # if it has only 1 vowel or 1 set of consecutive vowels, discard. (like "speed", "fled" etc.)
        if(substr($word, -2) == "es" || substr($word, -2) == "ed") {
            $doubleAndtripple_1 = preg_match_all('/[eaoui][eaoui]/', $word);
            if($doubleAndtripple_1 > 1 || preg_match_all('/[eaoui][^eaoui]/', $word) > 1) {
                if(substr($word, -3) == "ted" || substr($word, -3) == "ses" || substr($word, -3) == "ied" || substr($word, -3) == "ies") {
                    //pass
                }else{
                    $disc++;
                }
            }
        }
        $this->debugLog("#02 : ".$numVowels." / ".$syls." / ".$disc);

        // #3) discard trailing "e", except where ending is "le"
        $le_except = ['whole','mobile','pole','male','female','hale','pale','tale','sale','aisle','whale','while'];
        if(substr($word, -1) == "e") {
            if(substr($word, -2) == le && !in_array($word, $le_except)) {
                //pass
            }else{
                $disc++;
            }
        }
        $this->debugLog("#03 : ".$numVowels." / ".$syls." / ".$disc);

        // #4) check if consecutive vowels exists, triplets or pairs, count them as one.
        $doubleAndtripple = preg_match_all('/[eaoui][eaoui]/', $word);
        $tripple = preg_match_all('/[eaoui][eaoui][eaoui]/', $word);
        $disc += $doubleAndtripple + $tripple;
        $this->debugLog("#04 : ".$numVowels." / ".$syls." / ".$disc);

        // #5) count remaining vowels in word.
        $numVowels = preg_match_all('/[eaoui]/', $word);
        $this->debugLog("#05 : ".$numVowels." / ".$syls." / ".$disc);

        // #6) add one if starts with "mc"
        if(substr($word, 0, 2) == "mc") {
            $syls++;
        }
        $this->debugLog("#06 : ".$numVowels." / ".$syls." / ".$disc);

        // #7) add one if ends with "y" but is not surrouned by vowel
        $pattern = '/'.substr($word, -2, 1).'/';
        if(substr($word, -1) == "y" && preg_match_all($pattern, "aeoui") == 0) {
            $syls++;
        }
        $this->debugLog("#07 : ".$numVowels." / ".$syls." / ".$disc);

        // #8) add one if "y" is surrounded by non-vowels and is not in the last word.
        $arr = str_split($word);
        $max = count($arr);
        for ($i = 0; $i < $max; $i++) {
            $w = $arr[$i];
            if($w == 'y'){
                if($i != 0 && $i != $max-1){
                    if(preg_match('/'.$arr[$i-1].'/', "aeoui") != 0 &&
                        preg_match('/'.$arr[$i+1].'/', "aeoui") != 0) {
                            $syls++;
                        }
                }
            }
        }
        $this->debugLog("#08 : ".$numVowels." / ".$syls." / ".$disc);

        #9) if starts with "tri-" or "bi-" and is followed by a vowel, add one.
        if(substr($word, 0, 3) == "tri" && preg_match('/'.substr($word, 3, 1).'/', "aeoui") != 0) {
            // echo "match\n";
            $syls++;
        }
        if(substr($word, 0, 2) == "bi" && preg_match('/'.substr($word, 2, 1).'/', "aeoui") != 0) {
            $syls++;
        }
        $this->debugLog("#09 : ".$numVowels." / ".$syls." / ".$disc);

        #10) if ends with "-ian", should be counted as two syllables, except for "-tian" and "-cian"
        if(substr($word, -3) == "ian") {
            if(substr($word, -4) == "cian" || substr($word, -4) == "tian") {
                //pass;
            }else{
                $syls++;
            }
        }
        $this->debugLog("#10 : ".$numVowels." / ".$syls." / ".$disc);

        #11) if starts with "co-" and is followed by a vowel, check if exists in the double syllable dictionary, if not, check if in single dictionary and act accordingly.
        if(substr($word, 0, 2) == "co" && preg_match_all('/'.substr($word, 2, 1).'/', "aeoui")){
            if(in_array(substr($word, 0, 4), $co_two) || in_array(substr($word, 0, 5), $co_two) || in_array(substr($word, 0, 6), $co_two)){
                $syls++;
            }elseif (in_array(substr($word, 0, 4), $co_one) || in_array(substr($word, 0, 5), $co_one) || in_array(substr($word, 0, 6), $co_one)) {
                //pass;
            }else{
                $syls++;
            }
        }
        $this->debugLog("#11 : ".$numVowels." / ".$syls." / ".$disc);

        #12) if starts with "pre-" and is followed by a vowel, check if exists in the double syllable dictionary, if not, check if in single dictionary and act accordingly.
        if(substr($word, 0, 3) == "pre" && preg_match_all('/'.substr($word, 3, 1).'/', "aeoui")) {
            if(in_array(substr($word, 0, 6), $pre_one)){
                //pass
            }else{
                $syls++;
            }
        }
        $this->debugLog("#12 : ".$numVowels." / ".$syls." / ".$disc);

        #13) check for "-n't" and cross match with dictionary to add syllable.
        $negative = ["doesn't", "isn't", "shouldn't", "couldn't","wouldn't"];
        if(substr($word, -3) == "n't") {
            if(in_array($word, $negative)){
                $syls++;
            }else{
                //pass
            }
        }
        $this->debugLog("#13 : ".$numVowels." / ".$syls." / ".$disc);

        #14) Handling the exceptional words.
        if(in_array($word, $exception_del)){
            $disc++;
        }
        if(in_array($word, $exception_add)){
            $syls++;
        }
        $this->debugLog("#14 : ".$numVowels." / ".$syls." / ".$disc);

        return $numVowels + $syls - $disc;
    }

    private function debugLog($str) {
        if($this->debugMode){
            echo $str."\n";
        }
    }
}
$counter = new SyllableCounter(false);
$str = "Lorem Ipsum is simply dummy text of the printing and typesetting industry present preach";
echo $str." : ".($counter->count($str))."<br />\n";
?>
