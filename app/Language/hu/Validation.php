<?php
return [
    // Core Messages
	'noRuleSets'            => 'No rulesets specified in Validation configuration.',
	'ruleNotFound'          => '{0} is not a valid rule.',
	'groupNotFound'         => '{0} is not a validation rules group.',
	'groupNotArray'         => '{0} rule group must be an array.',
	'invalidTemplate'       => '{0} is not a valid Validation template.',

	// Rule Messages
	'alpha'                 => 'A {field} mező csak betűket tartalmazhat.',
	'alpha_dash'            => 'A he {field} mező csak betűket, aláhúzásjelet és közőjelet tartalmazhat.',
	'alpha_numeric'         => 'A {field} mezó csak betűket és számokat tartalmazhat.',
	'alpha_numeric_punct'   => 'A {field} mező csak betűket, számokat, szóközt és  ~ ! # $ % & * - _ + = | : . karaktereket tartalmazhat.',
	'alpha_numeric_space'   => 'A {field} mező csak betűket, számokat és szóközt tartalmazhat.',
	'alpha_space'           => 'A {field} mező csak betűket és szóközt tartalmazhat.',
	'decimal'               => 'A {field} mező csak decimális számot tartalmazhat.',
	'differs'               => 'A {field} mezőnek különböznie kell a {param} mezőtől.',
	'equals'                => 'A {field} mezőnek pontosan ennyinek kell lennie: {param}.',
	'exact_length'          => 'A {field} hosszának {param} karakternek kell lennie.',
	'greater_than'          => 'A {field} mezőnek nagyobbnak kell lennie, mint {param}.',
	'greater_than_equal_to' => 'A {field} mezőnek nagyobbnak vagy egyenlőnek kell lennie, mint {param}.',
	'hex'                   => 'A {field} mező csak hexadecimális karaktereket tartalmazhat.',
	'in_list'               => 'A {field} mezőnek a következőek egyikének kell lennie: {param}.',
	'integer'               => 'A {field} mező csak egész típusú lehet.',
	'is_natural'            => 'A {field} mező csak nem negatív egész számokat tartalmazhat.',
	'is_natural_no_zero'    => 'A {field} mező csak pozitív egész számokat tartalmazhat.',
	'is_not_unique'         => 'A {field} mező csak az adatbázisban létező adatot tartalmazhat.',
	'is_unique'             => 'A {field} mező csak egyedi értéket tartalmazhat.',
	'less_than'             => 'A {field} mező értéke kisebb lehet, mint {param}.',
	'less_than_equal_to'    => 'A {field} mező értéke kisebb, vagy egyenlő lehet, mint {param}.',
	'matches'               => 'A {field} mező nem azonos a(z) {param} mezővel.',
	'max_length'            => 'A {field} mező lefeljebb {param} karakter hosszú kell hogy legyen.',
	'min_length'            => 'A {field} mező legalább {param} karakter hosszú kell hogy legyen.',
	'not_equals'            => 'A {field} mező nem lehet: {param}.',
	'not_in_list'           => 'A {field} mező nem lehet a következő: {param}.',
	'numeric'               => 'A {field} mező csak számokat tartalmazhat.',
	'regex_match'           => 'A {field} mező nem megfelelő formátumú.',
	'required'              => 'A {field} mező megadása Kötelező',
	'required_with'         => 'A {field} mező megadása kötelező, amikor {param} meg van adva.',
	'required_without'      => 'A {field} mező megadása kötelező, amikor {param} nincs megadva.',
	'string'                => 'A {field} mező csak szöveg lehet.',
	'timezone'              => 'A {field} mezőnek érvényes időzónát tartalmazhat.',
	'valid_base64'          => 'A {field} mezőnek érvényes base64-et tartalmazhat.',
	'valid_email'           => 'A {field} mező csak érvényes email címet tartalmazhat.',
	'valid_emails'          => 'A {field} mező csak érvényes email címeket tartalmazhat.',
	'valid_ip'              => 'A {field} mező csak érvényes IP címet tartalmazhat.',
	'valid_url'             => 'A {field} mező csak érvényes URL címet tartalmazhat.',
	'valid_date'            => 'A {field} mező csak érvényes dátumot tartalmazhat.',

	// Credit Cards
	'valid_cc_num'          => 'Úgy tűnik, hogy a {field} mező érvénytelen bankkártya számot tartalmaz.',

	// Files
	'uploaded'              => '{field} - érvénytelen fájl.',
	'max_size'              => '{field} - túl nagy méretú fájl.',
	'is_image'              => '{field} - nem kép.',
	'mime_in'               => '{field} - érvénytelen fájltípus.',
	'ext_in'                => '{field} - érvénytelen kiterjesztés.',
	'max_dims'              => '{field} - nem kép, vagy túl magas vagy széles.',

    //custom rules
    'skill_not_exists' => 'Ez a szaktudás már hozzá van adva',
    'allowed_department' => 'Hibás érték',
    'is_allowed_item' => 'A {field} mező értéke hibás',
    'is_allowed_item_user' => 'A {field} mező értéke hibás',
    'next_month_date' => 'A {field} mező értéke csak következő havi dátum lehet'
];