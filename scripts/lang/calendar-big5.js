// ** I18N

// Calendar big5 language
// Author: Gary Fu, <gary@garyfu.idv.tw>
// Encoding: big5
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.
	
// full day names
Calendar._DN = new Array
("¬PґБ¤й",
 "¬PґБ¤@",
 "¬PґБ¤G",
 "¬PґБ¤T",
 "¬PґБҐ|",
 "¬PґБ¤­",
 "¬PґБ¤»",
 "¬PґБ¤й");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("¤й",
 "¤@",
 "¤G",
 "¤T",
 "Ґ|",
 "¤­",
 "¤»",
 "¤й");

// full month names
Calendar._MN = new Array
("¤@¤л",
 "¤G¤л",
 "¤T¤л",
 "Ґ|¤л",
 "¤­¤л",
 "¤»¤л",
 "¤C¤л",
 "¤K¤л",
 "¤E¤л",
 "¤Q¤л",
 "¤Q¤@¤л",
 "¤Q¤G¤л");

// short month names
Calendar._SMN = new Array
("¤@¤л",
 "¤G¤л",
 "¤T¤л",
 "Ґ|¤л",
 "¤­¤л",
 "¤»¤л",
 "¤C¤л",
 "¤K¤л",
 "¤E¤л",
 "¤Q¤л",
 "¤Q¤@¤л",
 "¤Q¤G¤л");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Гц©у";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"¤йґБїпѕЬ¤иЄk:\n" +
"- ЁПҐО \xab, \xbb «ц¶sҐiїпѕЬ¦~Ґч\n" +
"- ЁПҐО " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " «ц¶sҐiїпѕЬ¤лҐч\n" +
"- «ц¦н¤W­±Єє«ц¶sҐiҐHҐ[§ЦїпЁъ";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"®Й¶ЎїпѕЬ¤иЄk:\n" +
"- ВIА»Ґф¦уЄє®Й¶ЎіЎҐчҐiјWҐ[Ёд­И\n" +
"- ¦P®Й«цShiftБд¦AВIА»Ґiґо¤ЦЁд­И\n" +
"- ВIА»ЁГ©м¦ІҐiҐ[§Ц§пЕЬЄє­И";

Calendar._TT["PREV_YEAR"] = "¤W¤@¦~ («ц¦нїпіж)";
Calendar._TT["PREV_MONTH"] = "¤U¤@¦~ («ц¦нїпіж)";
Calendar._TT["GO_TODAY"] = "Ём¤µ¤й";
Calendar._TT["NEXT_MONTH"] = "¤W¤@¤л («ц¦нїпіж)";
Calendar._TT["NEXT_YEAR"] = "¤U¤@¤л («ц¦нїпіж)";
Calendar._TT["SEL_DATE"] = "їпѕЬ¤йґБ";
Calendar._TT["DRAG_TO_MOVE"] = "©м¦І";
Calendar._TT["PART_TODAY"] = " (¤µ¤й)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "±N %s ЕгҐЬ¦b«e";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Гці¬";
Calendar._TT["TODAY"] = "¤µ¤й";
Calendar._TT["TIME_PART"] = "ВIА»or©м¦ІҐi§пЕЬ®Й¶Ў(¦P®Й«цShift¬°ґо)";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "¶g";
Calendar._TT["TIME"] = "Time:";
