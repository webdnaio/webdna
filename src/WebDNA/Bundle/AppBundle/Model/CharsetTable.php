<?php

namespace WebDNA\Bundle\AppBundle\Model;

/**
 * Class CharsetTable
 *
 * Charsets were taken from http://webcheatsheet.com/html/character_sets_list.php#charset
 *
 * @package WebDNA\Bundle\AppBundle\Model
 */
class CharsetTable
{
    /**
     * Charsets contants.
     */
    const CHARSET_ASMO_708 = 1;
    const CHARSET_BIG5 = 2;
    const CHARSET_CP1026 = 3;
    const CHARSET_CP866 = 4;
    const CHARSET_CP870 = 5;
    const CHARSET_CSISO2022JP = 6;
    const CHARSET_DOS_720 = 7;
    const CHARSET_DOS_862 = 8;
    const CHARSET_EBCDIC_CP_US = 9;
    const CHARSET_EUC_CN = 10;
    const CHARSET_EUC_JP = 11;
    const CHARSET_EUC_KR = 12;
    const CHARSET_GB2312 = 13;
    const CHARSET_HZ_GB_2312 = 14;
    const CHARSET_IBM437 = 15;
    const CHARSET_IBM737 = 16;
    const CHARSET_IBM775 = 17;
    const CHARSET_IBM850 = 18;
    const CHARSET_IBM852 = 19;
    const CHARSET_IBM857 = 20;
    const CHARSET_IBM861 = 21;
    const CHARSET_IBM869 = 22;
    const CHARSET_ISO_2022_JP = 23;
    const CHARSET_ISO_2022_KR = 25;
    const CHARSET_ISO_8859_1 = 26;
    const CHARSET_ISO_8859_15 = 27;
    const CHARSET_ISO_8859_2 = 28;
    const CHARSET_ISO_8859_3 = 29;
    const CHARSET_ISO_8859_4 = 30;
    const CHARSET_ISO_8859_5 = 31;
    const CHARSET_ISO_8859_6 = 32;
    const CHARSET_ISO_8859_7 = 33;
    const CHARSET_ISO_8859_8 = 34;
    const CHARSET_ISO_8859_8_I = 35;
    const CHARSET_ISO_8859_9 = 36;
    const CHARSET_JOHAB = 37;
    const CHARSET_KOI8_R = 38;
    const CHARSET_KOI8_U = 39;
    const CHARSET_KS_C_5601_1987 = 40;
    const CHARSET_MACINTOSH = 41;
    const CHARSET_SHIFT_JIS = 42;
    const CHARSET_UNICODE = 43;
    const CHARSET_UNICODEFFFE = 44;
    const CHARSET_US_ASCII = 45;
    const CHARSET_UTF_7 = 46;
    const CHARSET_UTF_8 = 47;
    const CHARSET_WINDOWS_1250 = 48;
    const CHARSET_WINDOWS_1251 = 49;
    const CHARSET_WINDOWS_1252 = 50;
    const CHARSET_WINDOWS_1253 = 51;
    const CHARSET_WINDOWS_1254 = 52;
    const CHARSET_WINDOWS_1255 = 53;
    const CHARSET_WINDOWS_1256 = 54;
    const CHARSET_WINDOWS_1257 = 55;
    const CHARSET_WINDOWS_1258 = 56;
    const CHARSET_WINDOWS_874 = 57;
    const CHARSET_X_CHINESE_CNS = 58;
    const CHARSET_X_CHINESE_ETEN = 59;
    const CHARSET_X_EBCDIC_ARABIC = 60;
    const CHARSET_X_EBCDIC_CP_US_EURO = 61;
    const CHARSET_X_EBCDIC_CYRILLICRUSSIAN = 62;
    const CHARSET_X_EBCDIC_CYRILLICSERBIANBULGARIAN = 63;
    const CHARSET_X_EBCDIC_DENMARKNORWAY = 64;
    const CHARSET_X_EBCDIC_DENMARKNORWAY_EURO = 65;
    const CHARSET_X_EBCDIC_FINLANDSWEDEN = 66;
    const CHARSET_X_EBCDIC_FINLANDSWEDEN_EURO = 67;
    const CHARSET_X_EBCDIC_FRANCE_EURO = 68;
    const CHARSET_X_EBCDIC_GERMANY = 69;
    const CHARSET_X_EBCDIC_GERMANY_EURO = 70;
    const CHARSET_X_EBCDIC_GREEK = 71;
    const CHARSET_X_EBCDIC_GREEKMODERN = 72;
    const CHARSET_X_EBCDIC_HEBREW = 73;
    const CHARSET_X_EBCDIC_ICELANDIC = 74;
    const CHARSET_X_EBCDIC_ICELANDIC_EURO = 75;
    const CHARSET_X_EBCDIC_INTERNATIONAL_EURO = 76;
    const CHARSET_X_EBCDIC_ITALY = 77;
    const CHARSET_X_EBCDIC_ITALY_EURO = 78;
    const CHARSET_X_EBCDIC_JAPANESEANDJAPANESELATIN = 79;
    const CHARSET_X_EBCDIC_JAPANESEANDKANA = 80;
    const CHARSET_X_EBCDIC_JAPANESEANDUSCANADA = 81;
    const CHARSET_X_EBCDIC_JAPANESEKATAKANA = 82;
    const CHARSET_X_EBCDIC_KOREANANDKOREANEXTENDED = 83;
    const CHARSET_X_EBCDIC_KOREANEXTENDED = 84;
    const CHARSET_X_EBCDIC_SIMPLIFIEDCHINESE = 85;
    const CHARSET_X_EBCDIC_SPAIN = 86;
    const CHARSET_X_EBCDIC_SPAIN_EURO = 87;
    const CHARSET_X_EBCDIC_THAI = 88;
    const CHARSET_X_EBCDIC_TRADITIONALCHINESE = 89;
    const CHARSET_X_EBCDIC_TURKISH = 90;
    const CHARSET_X_EBCDIC_UK = 91;
    const CHARSET_X_EBCDIC_UK_EURO = 92;
    const CHARSET_X_EUROPA = 93;
    const CHARSET_X_IA5 = 94;
    const CHARSET_X_IA5_GERMAN = 95;
    const CHARSET_X_IA5_NORWEGIAN = 96;
    const CHARSET_X_IA5_SWEDISH = 97;
    const CHARSET_X_ISCII_AS = 98;
    const CHARSET_X_ISCII_BE = 99;
    const CHARSET_X_ISCII_DE = 100;
    const CHARSET_X_ISCII_GU = 101;
    const CHARSET_X_ISCII_KA = 102;
    const CHARSET_X_ISCII_MA = 103;
    const CHARSET_X_ISCII_OR = 104;
    const CHARSET_X_ISCII_PA = 105;
    const CHARSET_X_ISCII_TA = 106;
    const CHARSET_X_ISCII_TE = 107;
    const CHARSET_X_MAC_ARABIC = 108;
    const CHARSET_X_MAC_CE = 109;
    const CHARSET_X_MAC_CHINESESIMP = 110;
    const CHARSET_X_MAC_CHINESETRAD = 111;
    const CHARSET_X_MAC_CYRILLIC = 112;
    const CHARSET_X_MAC_GREEK = 113;
    const CHARSET_X_MAC_HEBREW = 114;
    const CHARSET_X_MAC_ICELANDIC = 115;
    const CHARSET_X_MAC_JAPANESE = 116;
    const CHARSET_X_MAC_KOREAN = 117;
    const CHARSET_X_MAC_TURKISH = 118;
    // Unknown encoding for all others
    const CHARSET_UNKNOWN = 9999;

    /**
     * Charset mapping table.
     *
     * @var array
     */
    public static $CHARSETS = array(
        self::CHARSET_ASMO_708 => 'asmo-708',
        self::CHARSET_BIG5 => 'big5',
        self::CHARSET_CP1026 => 'cp1026',
        self::CHARSET_CP866 => 'cp866',
        self::CHARSET_CP870 => 'cp870',
        self::CHARSET_CSISO2022JP => 'csiso2022jp',
        self::CHARSET_DOS_720 => 'dos-720',
        self::CHARSET_DOS_862 => 'dos-862',
        self::CHARSET_EBCDIC_CP_US => 'ebcdic-cp-us',
        self::CHARSET_EUC_CN => 'euc-cn',
        self::CHARSET_EUC_JP => 'euc-jp',
        self::CHARSET_EUC_KR => 'euc-kr',
        self::CHARSET_GB2312 => 'gb2312',
        self::CHARSET_HZ_GB_2312 => 'hz-gb-2312',
        self::CHARSET_IBM437 => 'ibm437',
        self::CHARSET_IBM737 => 'ibm737',
        self::CHARSET_IBM775 => 'ibm775',
        self::CHARSET_IBM850 => 'ibm850',
        self::CHARSET_IBM852 => 'ibm852',
        self::CHARSET_IBM857 => 'ibm857',
        self::CHARSET_IBM861 => 'ibm861',
        self::CHARSET_IBM869 => 'ibm869',
        self::CHARSET_ISO_2022_JP => 'iso-2022-jp',
        self::CHARSET_ISO_2022_JP => 'iso-2022-jp',
        self::CHARSET_ISO_2022_KR => 'iso-2022-kr',
        self::CHARSET_ISO_8859_1 => 'iso-8859-1',
        self::CHARSET_ISO_8859_15 => 'iso-8859-15',
        self::CHARSET_ISO_8859_2 => 'iso-8859-2',
        self::CHARSET_ISO_8859_3 => 'iso-8859-3',
        self::CHARSET_ISO_8859_4 => 'iso-8859-4',
        self::CHARSET_ISO_8859_5 => 'iso-8859-5',
        self::CHARSET_ISO_8859_6 => 'iso-8859-6',
        self::CHARSET_ISO_8859_7 => 'iso-8859-7',
        self::CHARSET_ISO_8859_8 => 'iso-8859-8',
        self::CHARSET_ISO_8859_8_I => 'iso-8859-8-i',
        self::CHARSET_ISO_8859_9 => 'iso-8859-9',
        self::CHARSET_JOHAB => 'johab',
        self::CHARSET_KOI8_R => 'koi8-r',
        self::CHARSET_KOI8_U => 'koi8-u',
        self::CHARSET_KS_C_5601_1987 => 'ks_c_5601-1987',
        self::CHARSET_MACINTOSH => 'macintosh',
        self::CHARSET_SHIFT_JIS => 'shift_jis',
        self::CHARSET_UNICODE => 'unicode',
        self::CHARSET_UNICODEFFFE => 'unicodefffe',
        self::CHARSET_US_ASCII => 'us-ascii',
        self::CHARSET_UTF_7 => 'utf-7',
        self::CHARSET_UTF_8 => 'utf-8',
        self::CHARSET_WINDOWS_1250 => 'windows-1250',
        self::CHARSET_WINDOWS_1251 => 'windows-1251',
        self::CHARSET_WINDOWS_1252 => 'windows-1252',
        self::CHARSET_WINDOWS_1253 => 'windows-1253',
        self::CHARSET_WINDOWS_1254 => 'windows-1254',
        self::CHARSET_WINDOWS_1255 => 'windows-1255',
        self::CHARSET_WINDOWS_1256 => 'windows-1256',
        self::CHARSET_WINDOWS_1257 => 'windows-1257',
        self::CHARSET_WINDOWS_1258 => 'windows-1258',
        self::CHARSET_WINDOWS_874 => 'windows-874',
        self::CHARSET_X_CHINESE_CNS => 'x-chinese-cns',
        self::CHARSET_X_CHINESE_ETEN => 'x-chinese-eten',
        self::CHARSET_X_EBCDIC_ARABIC => 'x-ebcdic-arabic',
        self::CHARSET_X_EBCDIC_CP_US_EURO => 'x-ebcdic-cp-us-euro',
        self::CHARSET_X_EBCDIC_CYRILLICRUSSIAN => 'x-ebcdic-cyrillicrussian',
        self::CHARSET_X_EBCDIC_CYRILLICSERBIANBULGARIAN => 'x-ebcdic-cyrillicserbianbulgarian',
        self::CHARSET_X_EBCDIC_DENMARKNORWAY => 'x-ebcdic-denmarknorway',
        self::CHARSET_X_EBCDIC_DENMARKNORWAY_EURO => 'x-ebcdic-denmarknorway-euro',
        self::CHARSET_X_EBCDIC_FINLANDSWEDEN => 'x-ebcdic-finlandsweden',
        self::CHARSET_X_EBCDIC_FINLANDSWEDEN_EURO => 'x-ebcdic-finlandsweden-euro',
        self::CHARSET_X_EBCDIC_FRANCE_EURO => 'x-ebcdic-france-euro',
        self::CHARSET_X_EBCDIC_GERMANY => 'x-ebcdic-germany',
        self::CHARSET_X_EBCDIC_GERMANY_EURO => 'x-ebcdic-germany-euro',
        self::CHARSET_X_EBCDIC_GREEK => 'x-ebcdic-greek',
        self::CHARSET_X_EBCDIC_GREEKMODERN => 'x-ebcdic-greekmodern',
        self::CHARSET_X_EBCDIC_HEBREW => 'x-ebcdic-hebrew',
        self::CHARSET_X_EBCDIC_ICELANDIC => 'x-ebcdic-icelandic',
        self::CHARSET_X_EBCDIC_ICELANDIC_EURO => 'x-ebcdic-icelandic-euro',
        self::CHARSET_X_EBCDIC_INTERNATIONAL_EURO => 'x-ebcdic-international-euro',
        self::CHARSET_X_EBCDIC_ITALY => 'x-ebcdic-italy',
        self::CHARSET_X_EBCDIC_ITALY_EURO => 'x-ebcdic-italy-euro',
        self::CHARSET_X_EBCDIC_JAPANESEANDJAPANESELATIN => 'x-ebcdic-japaneseandjapaneselatin',
        self::CHARSET_X_EBCDIC_JAPANESEANDKANA => 'x-ebcdic-japaneseandkana',
        self::CHARSET_X_EBCDIC_JAPANESEANDUSCANADA => 'x-ebcdic-japaneseanduscanada',
        self::CHARSET_X_EBCDIC_JAPANESEKATAKANA => 'x-ebcdic-japanesekatakana',
        self::CHARSET_X_EBCDIC_KOREANANDKOREANEXTENDED => 'x-ebcdic-koreanandkoreanextended',
        self::CHARSET_X_EBCDIC_KOREANEXTENDED => 'x-ebcdic-koreanextended',
        self::CHARSET_X_EBCDIC_SIMPLIFIEDCHINESE => 'x-ebcdic-simplifiedchinese',
        self::CHARSET_X_EBCDIC_SPAIN => 'x-ebcdic-spain',
        self::CHARSET_X_EBCDIC_SPAIN_EURO => 'x-ebcdic-spain-euro',
        self::CHARSET_X_EBCDIC_THAI => 'x-ebcdic-thai',
        self::CHARSET_X_EBCDIC_TRADITIONALCHINESE => 'x-ebcdic-traditionalchinese',
        self::CHARSET_X_EBCDIC_TURKISH => 'x-ebcdic-turkish',
        self::CHARSET_X_EBCDIC_UK => 'x-ebcdic-uk',
        self::CHARSET_X_EBCDIC_UK_EURO => 'x-ebcdic-uk-euro',
        self::CHARSET_X_EUROPA => 'x-europa',
        self::CHARSET_X_IA5 => 'x-ia5',
        self::CHARSET_X_IA5_GERMAN => 'x-ia5-german',
        self::CHARSET_X_IA5_NORWEGIAN => 'x-ia5-norwegian',
        self::CHARSET_X_IA5_SWEDISH => 'x-ia5-swedish',
        self::CHARSET_X_ISCII_AS => 'x-iscii-as',
        self::CHARSET_X_ISCII_BE => 'x-iscii-be',
        self::CHARSET_X_ISCII_DE => 'x-iscii-de',
        self::CHARSET_X_ISCII_GU => 'x-iscii-gu',
        self::CHARSET_X_ISCII_KA => 'x-iscii-ka',
        self::CHARSET_X_ISCII_MA => 'x-iscii-ma',
        self::CHARSET_X_ISCII_OR => 'x-iscii-or',
        self::CHARSET_X_ISCII_PA => 'x-iscii-pa',
        self::CHARSET_X_ISCII_TA => 'x-iscii-ta',
        self::CHARSET_X_ISCII_TE => 'x-iscii-te',
        self::CHARSET_X_MAC_ARABIC => 'x-mac-arabic',
        self::CHARSET_X_MAC_CE => 'x-mac-ce',
        self::CHARSET_X_MAC_CHINESESIMP => 'x-mac-chinesesimp',
        self::CHARSET_X_MAC_CHINESETRAD => 'x-mac-chinesetrad',
        self::CHARSET_X_MAC_CYRILLIC => 'x-mac-cyrillic',
        self::CHARSET_X_MAC_GREEK => 'x-mac-greek',
        self::CHARSET_X_MAC_HEBREW => 'x-mac-hebrew',
        self::CHARSET_X_MAC_ICELANDIC => 'x-mac-icelandic',
        self::CHARSET_X_MAC_JAPANESE => 'x-mac-japanese',
        self::CHARSET_X_MAC_KOREAN => 'x-mac-korean',
        self::CHARSET_X_MAC_TURKISH => 'x-mac-turkish',
        //
        self::CHARSET_UNKNOWN => null,
    );

    /**
     * Return charset ID by name.
     *
     * @param $charset
     * @return int
     */
    public function getCharsetId($charset)
    {
        $charset = mb_strtolower($charset);

        return in_array($charset, self::$CHARSETS)
            ? array_search($charset, self::$CHARSETS)
            : self::CHARSET_UNKNOWN;
    }

    /**
     * Return charset name by ID.
     *
     * @param $charsetId
     * @return mixed
     */
    public function getCharsetName($charsetId)
    {
        return array_key_exists($charsetId, self::$CHARSETS)
            ? self::$CHARSETS[$charsetId]
            : self::$CHARSETS[self::CHARSET_UNKNOWN];
    }
}
