# SyllableCounter
Utilities for Counting Syllables in English by Javascript or PHP.   
Referring source (http://eayd.in/?p=232)

## Install
Put js or php file to anywhere easy to use.

## Usage
####PHP
    require_once(SyllableCounter.php);
    $counter = new SyllableCounter();
    $str = "Lorem Ipsum is simply dummy text of the printing and typesetting industry";
    echo $str." : ".($counter->count($str))."<br />\n";

####JS
    <script src="SyllableCounter.js"></script>
    <script>
    	var counter = new SyllableCounter();
    	var str = "Lorem Ipsum is simply dummy text of the printing and typesetting industry";
    	console.log(str+" : "+counter.count(str));
    </script>

If you pass true to constructor like `new SyllableCounter(true)`. You can check algorithm flow step by step.

    countSyllablesInWord : industry
    #01 : 0 / 0 / 0
    #02 : 0 / 0 / 0
    #03 : 0 / 0 / 0
    #04 : 0 / 0 / 0
    #05 : 2 / 0 / 0
    #06 : 2 / 0 / 0
    #07 : 2 / 1 / 0
    #08 : 2 / 1 / 0
    #09 : 2 / 1 / 0
    #10 : 2 / 1 / 0
    #11 : 2 / 1 / 0
    #12 : 2 / 1 / 0
    #13 : 2 / 1 / 0
    #14 : 2 / 1 / 0


## TODO
- C++
- C#
- other languages

## Licence
[MIT](https://github.com/tcnksm/tool/blob/master/LICENCE)

## Author
[yasnis](https://github.com/yasnis)
