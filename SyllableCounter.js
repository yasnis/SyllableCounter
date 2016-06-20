var SyllableCounter = (function () {
    function SyllableCounter(_debug) {
        if (_debug === void 0) { _debug = false; }
        SyllableCounter.debugMode = _debug;
    }
    SyllableCounter.prototype.count = function (sentence) {
        var words = sentence.split(/ |\s|,|\./);
        var c = 0;
        for (var key in words) {
            var word = words[key];
            var n = this.countWord(word);
            SyllableCounter.debugLog("countSyllablesInWord : " + word + " (" + n + ").");
            c += n;
        }
        return c;
    };
    SyllableCounter.prototype.countWord = function (word) {
        SyllableCounter.debugLog("countSyllablesInWord : " + word);
        word = word.toLowerCase();
        if (word.length <= 3) {
            return 1;
        }
        var syls = 0;
        var disc = 0;
        var numVowels = 0;
        SyllableCounter.debugLog("#01 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(-1) == "es" || word.substr(-1) == "ed") {
            var doubleAndtripple_1 = SyllableCounter.getMatchCount(word, /[eaoui][eaoui]/g);
            var doubleAndtripple_2 = SyllableCounter.getMatchCount(word, /[eaoui][^eaoui]/g);
            if (doubleAndtripple_1 > 1 || doubleAndtripple_2 > 1) {
                if (word.substr(-3) == "ted" || word.substr(-3) == "ses" || word.substr(-3) == "ied" || word.substr(-3) == "ies") {
                }
                else {
                    disc++;
                }
            }
        }
        SyllableCounter.debugLog("#02 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(-1) == "e") {
            if (word.substr(-2) == "le" && SyllableCounter.le_except.indexOf(word) == -1) {
            }
            else {
                disc++;
            }
        }
        SyllableCounter.debugLog("#03 : " + numVowels + " / " + syls + " / " + disc);
        var doubleAndtripple = SyllableCounter.getMatchCount(word, /[eaoui][eaoui]/g);
        var tripple = SyllableCounter.getMatchCount(word, /[eaoui][eaoui][eaoui]/g);
        disc += doubleAndtripple + tripple;
        SyllableCounter.debugLog("#04 : " + numVowels + " / " + syls + " / " + disc);
        numVowels = SyllableCounter.getMatchCount(word, /[eaoui]/g);
        SyllableCounter.debugLog("#05 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(0, 2) == "mc") {
            syls++;
        }
        SyllableCounter.debugLog("#06 : " + numVowels + " / " + syls + " / " + disc);
        var pattern = word.substr(-2, 1);
        if (word.substr(-1) == "y" && "aeoui".indexOf(pattern) == -1) {
            syls++;
        }
        SyllableCounter.debugLog("#07 : " + numVowels + " / " + syls + " / " + disc);
        var max = word.length;
        for (var i = 0; i < max; i++) {
            if (word.charAt(i) == "y") {
                if (i != 0 && i != max - 1) {
                    if (SyllableCounter.getMatchCount("aeoui", new RegExp(word.charAt(i - 1))) != 0 &&
                        SyllableCounter.getMatchCount("aeoui", new RegExp(word.charAt(i + 1))) != 0) {
                        syls++;
                    }
                }
            }
        }
        SyllableCounter.debugLog("#08 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(0, 3) == "tri" && SyllableCounter.getMatchCount("aeoui", new RegExp(word.substr(3, 1))) != 0) {
            syls++;
        }
        if (word.substr(0, 2) == "bi" && SyllableCounter.getMatchCount("aeoui", new RegExp(word.substr(2, 1))) != 0) {
            syls++;
        }
        SyllableCounter.debugLog("#09 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(-3) == "ian") {
            if (word.substr(-4) == "cian" || word.substr(-4) == "tian") {
            }
            else {
                syls++;
            }
        }
        SyllableCounter.debugLog("#10 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(0, 2) == "co" && SyllableCounter.getMatchCount("aeoui", new RegExp(word.substr(2, 1)))) {
            if (SyllableCounter.co_two.indexOf(word.substr(0, 4)) > -1 ||
                SyllableCounter.co_two.indexOf(word.substr(0, 5)) > -1 ||
                SyllableCounter.co_two.indexOf(word.substr(0, 6)) > -1) {
                syls++;
            }
            else if (SyllableCounter.co_one.indexOf(word.substr(0, 4)) > -1 ||
                SyllableCounter.co_one.indexOf(word.substr(0, 5)) > -1 ||
                SyllableCounter.co_one.indexOf(word.substr(0, 6)) > -1) {
            }
            else {
                syls++;
            }
        }
        SyllableCounter.debugLog("#11 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(0, 3) == "pre" && SyllableCounter.getMatchCount("aeoui", new RegExp(word.substr(3, 1)))) {
            if (SyllableCounter.pre_one.indexOf(word.substr(0, 6))) {
            }
            else {
                syls++;
            }
        }
        SyllableCounter.debugLog("#12 : " + numVowels + " / " + syls + " / " + disc);
        if (word.substr(-3) == "n't") {
            if (SyllableCounter.negative.indexOf(word)) {
                syls++;
            }
            else {
            }
        }
        SyllableCounter.debugLog("#13 : " + numVowels + " / " + syls + " / " + disc);
        if (SyllableCounter.exception_del.indexOf(word)) {
            disc++;
        }
        if (SyllableCounter.exception_add.indexOf(word)) {
            syls++;
        }
        SyllableCounter.debugLog("#14 : " + numVowels + " / " + syls + " / " + disc);
        return numVowels + syls - disc;
    };
    SyllableCounter.getMatchCount = function (str, regexp) {
        var result;
        var r = 0;
        while (result = regexp.exec(str)) {
            r++;
        }
        return r;
    };
    SyllableCounter.debugLog = function (str) {
        if (SyllableCounter.debugMode) {
            console.log(str);
        }
    };
    SyllableCounter.debugMode = false;
    SyllableCounter.exception_add = ["serious", "crucial"];
    SyllableCounter.exception_del = ["fortunately", "unfortunately"];
    SyllableCounter.co_one = ["cool", "coach", "coat", "coal", "count", "could", "coin", "coarse", "coup", "coif", "cook", "coign", "coiffe", "coof", "court"];
    SyllableCounter.co_two = ["coapt", "coed", "coinci"];
    SyllableCounter.pre_one = ["preach"];
    SyllableCounter.le_except = ["whole", "mobile", "pole", "male", "female", "hale", "pale", "tale", "sale", "aisle", "whale", "while"];
    SyllableCounter.negative = ["doesn't", "isn't", "shouldn't", "couldn't", "wouldn't"];
    return SyllableCounter;
})();
