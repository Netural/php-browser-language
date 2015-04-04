<?php
// Detect Browser langauge
// Browsersprache ermitteln
function lang_getfrombrowser ($allowed_languages, $default_language, $lang_variable = null, $strict_mode = true) {
    // Use $_SERVER['HTTP_ACCEPT_LANGUAGE'], if there is no language variable set
    // $_SERVER['HTTP_ACCEPT_LANGUAGE'] verwenden, wenn keine Sprachvariable mitgegeben wurde
    if ($lang_variable === null) {
        $lang_variable = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

    // was there any language information sent?
    // wurde irgendwelche Information mitgeschickt?
    if (empty($lang_variable)) {
        // No? => Set default language
        // Nein? => Standardsprache zurückgeben
        return $default_language;
    }

    // split up the header
    // Den Header auftrennen
    $accepted_languages = preg_split('/,\s*/', $lang_variable);

    // default value configuration
    // Die Standardwerte einstellen
    $current_lang = $default_language;
    $current_q = 0;

    // Work over all languages
    // Nun alle mitgegebenen Sprachen abarbeiten
    foreach ($accepted_languages as $accepted_language) {
        // Alle Infos über diese Sprache rausholen
        $res = preg_match ('/^([a-z]{1,8}(?:-[a-z]{1,8})*)'.
                           '(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i', $accepted_language, $matches);

        // was it in the right syntax?
        // war die Syntax gültig?
        if (!$res) {
            // no? just ignore it
            // Nein? Dann ignorieren
            continue;
        }

        // get languagecode and split it in single entries
        // Sprachcode holen und dann sofort in die Einzelteile trennen
        $lang_code = explode ('-', $matches[1]);

        // is there a quality also sent?
        // Wurde eine Qualität mitgegeben?
        if (isset($matches[2])) {
            // using the quality
            // die Qualität benutzen
            $lang_quality = (float)$matches[2];
        } else {
            // if not simply use quality 1 as default
            // Kompabilitätsmodus: Qualität 1 annehmen
            $lang_quality = 1.0;
        }

        // do this till the language code is empty
        // Bis der Sprachcode leer ist...
        while (count ($lang_code)) {
            // which language code's are supported/allowed
            // mal sehen, ob der Sprachcode angeboten wird
            if (in_array (strtolower (join ('-', $lang_code)), $allowed_languages)) {
                // have a look at the quality
                // Qualität anschauen
                if ($lang_quality > $current_q) {
                    // use/set this language
                    // diese Sprache verwenden
                    $current_lang = strtolower (join ('-', $lang_code));
                    $current_q = $lang_quality;
                    // get out of the inner while loop
                    // Hier die innere while-Schleife verlassen
                    break;
                }
            }
            // If we are in the strict mode we are not allowed to use the minified version
            // Wenn wir im strengen Modus sind, die Sprache nicht versuchen zu minimalisieren
            if ($strict_mode) {
                // get out of the inner while loop then
                // innere While-Schleife aufbrechen
                break;
            }
            // remove the right side of the language code
            // den rechtesten Teil des Sprachcodes abschneiden
            array_pop ($lang_code);
        }
    }
    // return the found languages
    // die gefundene Sprache zurückgeben
    return $current_lang;
}
?>
