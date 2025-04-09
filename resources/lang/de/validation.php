<?php

return [
    'required' => 'Das Feld :attribute ist erforderlich.',
    'email' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'confirmed'=> 'Die :attribute-Best&auml;tigung stimmt nicht &uuml;berein.',
    'numeric' => 'Das Feld :attribute muss eine Zahl sein.',
    'unique' => 'Dieses :attribute wurde bereits verwendet.',
    'exists' => 'Das ausgewählte :attribute ist ungültig.',
    'accepted'             => 'Das Feld :attribute muss akzeptiert werden.',
    'active_url'           => 'Das Feld :attribute enthält keine gültige URL.',
    'after'                => 'Das Feld :attribute muss ein Datum nach :date sein.',
    'after_or_equal'       => 'Das Feld :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha'                => 'Das Feld :attribute darf nur Buchstaben enthalten.',
    'alpha_dash'           => 'Das Feld :attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num'            => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'                => 'Das Feld :attribute muss ein Array sein.',
    'before'               => 'Das Feld :attribute muss ein Datum vor :date sein.',
    'before_or_equal'      => 'Das Feld :attribute muss ein Datum vor oder gleich :date sein.',
    'between'              => [
        'numeric' => 'Das Feld :attribute muss zwischen :min und :max liegen.',
        'file'    => 'Die Datei :attribute muss zwischen :min und :max Kilobyte groß sein.',
        'string'  => 'Der Text :attribute muss zwischen :min und :max Zeichen lang sein.',
        'array'   => 'Das Array :attribute muss zwischen :min und :max Elemente enthalten.',
    ],
    'boolean'              => 'Das Feld :attribute muss entweder true oder false sein.',
    'min' => [
        'numeric' => 'Das :attribute muss mindestens :min sein.',
        'file' => 'Die :attribute muss mindestens :min Kilobyte groß sein.',
        'string' => 'Das :attribute muss mindestens :min Zeichen lang sein.',
        'array' => 'Das :attribute muss mindestens :min Elemente enthalten.',
    ],
    'max' => [
        'numeric' => 'Das :attribute darf maximal :max sein.',
        'file' => 'Die :attribute darf maximal :max Kilobyte groß sein.',
        'string' => 'Das :attribute darf maximal :max Zeichen lang sein.',
        'array' => 'Das :attribute darf maximal :max Elemente enthalten.',
    ],
    'captcha' => 'Das Feld :attribute ist ungültig.',
    'different' => 'Das :attribute muss sich von :other unterscheiden.',
];

