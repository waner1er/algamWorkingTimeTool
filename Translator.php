<?php
class Translator {
    private $translations;
    private $lang = 'fr'; // Default language is French

    public function __construct() {
        $this->translations = [
            'fr' => [
                'export' => "exporter",
                'friday' => 'vendredi',
                'monday' => 'lundi',
                'my-hours' => 'mes heures',
                'thursday' => 'jeudi',
                'total' => 'total',
                'tuesday' => 'mardi',
                'wednesday' => 'mercredi',
                'week-choice'=> 'choix de la semaine'
            ],
            'en' => [
                "export" => "export",
                'friday' => 'friday',
                'monday' => 'monday',
                'my-hours' => 'my hours',
                'thursday' => 'thursday',
                'total' => 'total',
                'tuesday' => 'tuesday',
                'wednesday' => 'wednesday',
                'week-choice'=> 'week choice'
            ]
        ];
    }
    public function getLang() {
        return $this->lang;
    }
    public function setLang($lang) {
        $this->lang = $lang;
    }

    public function __invoke(string $text) {
        if (isset($this->translations[$this->lang]) && array_key_exists($text, $this->translations[$this->lang])) {
            // return the translation if it exists
            return $this->translations[$this->lang][$text];
        } else {
            // return untranslated text if the translation does not exist
            return $text;
        }
    }
}