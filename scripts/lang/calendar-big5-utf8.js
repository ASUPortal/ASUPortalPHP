// ** I18N

// Calendar big5-utf8 language
// Author: Gary Fu, <gary@garyfu.idv.tw>
// Encoding: utf8
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.
	
// full day names
Calendar._DN = new Array
("жџжњџж—Ґ",
 "жџжњџдёЂ",
 "жџжњџдєЊ",
 "жџжњџдё‰",
 "жџжњџе››",
 "жџжњџдє”",
 "жџжњџе…­",
 "жџжњџж—Ґ");

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
("ж—Ґ",
 "дёЂ",
 "дєЊ",
 "дё‰",
 "е››",
 "дє”",
 "е…­",
 "ж—Ґ");

// full month names
Calendar._MN = new Array
("дёЂжњ€",
 "дєЊжњ€",
 "дё‰жњ€",
 "е››жњ€",
 "дє”жњ€",
 "е…­жњ€",
 "дёѓжњ€",
 "е…«жњ€",
 "д№ќжњ€",
 "еЌЃжњ€",
 "еЌЃдёЂжњ€",
 "еЌЃдєЊжњ€");

// short month names
Calendar._SMN = new Array
("дёЂжњ€",
 "дєЊжњ€",
 "дё‰жњ€",
 "е››жњ€",
 "дє”жњ€",
 "е…­жњ€",
 "дёѓжњ€",
 "е…«жњ€",
 "д№ќжњ€",
 "еЌЃжњ€",
 "еЌЃдёЂжњ€",
 "еЌЃдєЊжњ€");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "й—њж–ј";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"ж—ҐжњџйЃёж“‡ж–№жі•:\n" +
"- дЅїз”Ё \xab, \xbb жЊ‰й€•еЏЇйЃёж“‡е№ґд»Ѕ\n" +
"- дЅїз”Ё " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " жЊ‰й€•еЏЇйЃёж“‡жњ€д»Ѕ\n" +
"- жЊ‰дЅЏдёЉйќўзљ„жЊ‰й€•еЏЇд»ҐеЉ еї«йЃёеЏ–";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"ж™‚й–“йЃёж“‡ж–№жі•:\n" +
"- й»ћж“Љд»»дЅ•зљ„ж™‚й–“йѓЁд»ЅеЏЇеўћеЉ е…¶еЂј\n" +
"- еђЊж™‚жЊ‰ShiftйЌµе†Ќй»ћж“ЉеЏЇжё›е°‘е…¶еЂј\n" +
"- й»ћж“Љдё¦ж‹–ж›іеЏЇеЉ еї«ж”№и®Љзљ„еЂј";

Calendar._TT["PREV_YEAR"] = "дёЉдёЂе№ґ (жЊ‰дЅЏйЃёе–®)";
Calendar._TT["PREV_MONTH"] = "дё‹дёЂе№ґ (жЊ‰дЅЏйЃёе–®)";
Calendar._TT["GO_TODAY"] = "е€°д»Љж—Ґ";
Calendar._TT["NEXT_MONTH"] = "дёЉдёЂжњ€ (жЊ‰дЅЏйЃёе–®)";
Calendar._TT["NEXT_YEAR"] = "дё‹дёЂжњ€ (жЊ‰дЅЏйЃёе–®)";
Calendar._TT["SEL_DATE"] = "йЃёж“‡ж—Ґжњџ";
Calendar._TT["DRAG_TO_MOVE"] = "ж‹–ж›і";
Calendar._TT["PART_TODAY"] = " (д»Љж—Ґ)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "е°‡ %s йЎЇз¤єењЁе‰Ќ";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "й—њй–‰";
Calendar._TT["TODAY"] = "д»Љж—Ґ";
Calendar._TT["TIME_PART"] = "й»ћж“Љorж‹–ж›іеЏЇж”№и®Љж™‚й–“(еђЊж™‚жЊ‰Shiftз‚єжё›)";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "йЂ±";
Calendar._TT["TIME"] = "Time:";
