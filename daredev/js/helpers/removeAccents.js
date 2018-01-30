/**
 * Remove accents from Greek letters.
 * Useful for live searches.
 * Use it like that: ('string').removeAccents();
 * @link http://semplicewebsites.com/removing-accents-javascript
 */
var letters = {
    '\u0386' : '\u0391', // 'Ά':'Α'
    '\u0388' : '\u0395', // 'Έ':'Ε'
    '\u0389' : '\u0397', // 'Ή':'Η'
    '\u038A' : '\u0399', // 'Ί':'Ι'
    '\u038C' : '\u039F', // 'Ό':'Ο'
    '\u038E' : '\u03A5', // 'Ύ':'Υ'
    '\u038F' : '\u03A9', // 'Ώ':'Ω'
    '\u0390' : '\u03B9', // 'ΐ':'ι'
    '\u03AA' : '\u0399', // 'Ϊ':'Ι'
    '\u03AB' : '\u03A5', // 'Ϋ':'Υ'
    '\u03AC' : '\u03B1', // 'ά':'α'
    '\u03AD' : '\u03B5', // 'έ':'ε'
    '\u03AE' : '\u03B7', // 'ή':'η'
    '\u03AF' : '\u03B9', // 'ί':'ι'
    '\u03B0' : '\u03C5', // 'ΰ':'υ'
    '\u03CA' : '\u03B9', // 'ϊ':'ι'
    '\u03CB' : '\u03C5', // 'ϋ':'υ'
    '\u03CC' : '\u03BF', // 'ό':'ο'
    '\u03CD' : '\u03C5', // 'ύ':'υ'
    '\u03CE' : '\u03C9' // 'ώ':'ω'
};

String.prototype.removeAccents = function() {
    return this.replace(/[^A-Za-z0-9]/g, function(x) { return letters[x] || x; })
};