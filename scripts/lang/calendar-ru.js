// ** I18N

// Calendar RU language
// Translation: Sly Golovanov, http://golovanov.net, <sly@golovanov.net>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("РІРѕСЃРєСЂРµСЃРµРЅСЊРµ",
 "РїРѕРЅРµРґРµР»СЊРЅРёРє",
 "РІС‚РѕСЂРЅРёРє",
 "СЃСЂРµРґР°",
 "С‡РµС‚РІРµСЂРі",
 "РїСЏС‚РЅРёС†Р°",
 "СЃСѓР±Р±РѕС‚Р°",
 "РІРѕСЃРєСЂРµСЃРµРЅСЊРµ");

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
("РІСЃРє",
 "РїРѕРЅ",
 "РІС‚СЂ",
 "СЃСЂРґ",
 "С‡РµС‚",
 "РїСЏС‚",
 "СЃСѓР±",
 "РІСЃРє");

// full month names
Calendar._MN = new Array
("СЏРЅРІР°СЂСЊ",
 "С„РµРІСЂР°Р»СЊ",
 "РјР°СЂС‚",
 "Р°РїСЂРµР»СЊ",
 "РјР°Р№",
 "РёСЋРЅСЊ",
 "РёСЋР»СЊ",
 "Р°РІРіСѓСЃС‚",
 "СЃРµРЅС‚СЏР±СЂСЊ",
 "РѕРєС‚СЏР±СЂСЊ",
 "РЅРѕСЏР±СЂСЊ",
 "РґРµРєР°Р±СЂСЊ");

// short month names
Calendar._SMN = new Array
("СЏРЅРІ",
 "С„РµРІ",
 "РјР°СЂ",
 "Р°РїСЂ",
 "РјР°Р№",
 "РёСЋРЅ",
 "РёСЋР»",
 "Р°РІРі",
 "СЃРµРЅ",
 "РѕРєС‚",
 "РЅРѕСЏ",
 "РґРµРє");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Рћ РєР°Р»РµРЅРґР°СЂРµ...";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"РљР°Рє РІС‹Р±СЂР°С‚СЊ РґР°С‚Сѓ:\n" +
"- РџСЂРё РїРѕРјРѕС‰Рё РєРЅРѕРїРѕРє \xab, \xbb РјРѕР¶РЅРѕ РІС‹Р±СЂР°С‚СЊ РіРѕРґ\n" +
"- РџСЂРё РїРѕРјРѕС‰Рё РєРЅРѕРїРѕРє " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " РјРѕР¶РЅРѕ РІС‹Р±СЂР°С‚СЊ РјРµСЃСЏС†\n" +
"- РџРѕРґРµСЂР¶РёС‚Рµ СЌС‚Рё РєРЅРѕРїРєРё РЅР°Р¶Р°С‚С‹РјРё, С‡С‚РѕР±С‹ РїРѕСЏРІРёР»РѕСЃСЊ РјРµРЅСЋ Р±С‹СЃС‚СЂРѕРіРѕ РІС‹Р±РѕСЂР°.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"РљР°Рє РІС‹Р±СЂР°С‚СЊ РІСЂРµРјСЏ:\n" +
"- РџСЂРё РєР»РёРєРµ РЅР° С‡Р°СЃР°С… РёР»Рё РјРёРЅСѓС‚Р°С… РѕРЅРё СѓРІРµР»РёС‡РёРІР°СЋС‚СЃСЏ\n" +
"- РїСЂРё РєР»РёРєРµ СЃ РЅР°Р¶Р°С‚РѕР№ РєР»Р°РІРёС€РµР№ Shift РѕРЅРё СѓРјРµРЅСЊС€Р°СЋС‚СЃСЏ\n" +
"- РµСЃР»Рё РЅР°Р¶Р°С‚СЊ Рё РґРІРёРіР°С‚СЊ РјС‹С€РєРѕР№ РІР»РµРІРѕ/РІРїСЂР°РІРѕ, РѕРЅРё Р±СѓРґСѓС‚ РјРµРЅСЏС‚СЊСЃСЏ Р±С‹СЃС‚СЂРµРµ.";

Calendar._TT["PREV_YEAR"] = "РќР° РіРѕРґ РЅР°Р·Р°Рґ (СѓРґРµСЂР¶РёРІР°С‚СЊ РґР»СЏ РјРµРЅСЋ)";
Calendar._TT["PREV_MONTH"] = "РќР° РјРµСЃСЏС† РЅР°Р·Р°Рґ (СѓРґРµСЂР¶РёРІР°С‚СЊ РґР»СЏ РјРµРЅСЋ)";
Calendar._TT["GO_TODAY"] = "РЎРµРіРѕРґРЅСЏ";
Calendar._TT["NEXT_MONTH"] = "РќР° РјРµСЃСЏС† РІРїРµСЂРµРґ (СѓРґРµСЂР¶РёРІР°С‚СЊ РґР»СЏ РјРµРЅСЋ)";
Calendar._TT["NEXT_YEAR"] = "РќР° РіРѕРґ РІРїРµСЂРµРґ (СѓРґРµСЂР¶РёРІР°С‚СЊ РґР»СЏ РјРµРЅСЋ)";
Calendar._TT["SEL_DATE"] = "Р’С‹Р±РµСЂРёС‚Рµ РґР°С‚Сѓ";
Calendar._TT["DRAG_TO_MOVE"] = "РџРµСЂРµС‚Р°СЃРєРёРІР°Р№С‚Рµ РјС‹С€РєРѕР№";
Calendar._TT["PART_TODAY"] = " (СЃРµРіРѕРґРЅСЏ)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "РџРµСЂРІС‹Р№ РґРµРЅСЊ РЅРµРґРµР»Рё Р±СѓРґРµС‚ %s";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Р—Р°РєСЂС‹С‚СЊ";
Calendar._TT["TODAY"] = "РЎРµРіРѕРґРЅСЏ";
Calendar._TT["TIME_PART"] = "(Shift-)РєР»РёРє РёР»Рё РЅР°Р¶Р°С‚СЊ Рё РґРІРёРіР°С‚СЊ";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%e %b, %a";

Calendar._TT["WK"] = "РЅРµРґ";
Calendar._TT["TIME"] = "Р’СЂРµРјСЏ:";
