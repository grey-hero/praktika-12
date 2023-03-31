<?
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];
function getFullnameFromParts($surname, $name, $patronomyc){
	return $surname." ".$name." ".$patronomyc;
}
function getPartsFromFullname($fullName){
	$parts = explode(" ", $fullName);
	return ['surname' => $parts[0], 'name' => $parts[1], 'patronomyc' => $parts[2]];
}
function getShortName($fullName){
	$parts = getPartsFromFullname($fullName);
	return $parts['name']." ".mb_substr($parts['surname'], 0, 1).".";
}
function getGenderFromName($fullName){

	$parts = getPartsFromFullname($fullName);

	$malePoints = 0;
	$femalePoints = 0;

	if(mb_substr($parts['surname'], -2) == "ва"){
		$femalePoints++;
	}
	if(mb_substr($parts['name'], -1) == "а"){
		$femalePoints++;
	}
	if(mb_substr($parts['patronomyc'], -3) == "вна"){
		$femalePoints++;
	}
	if(mb_substr($parts['surname'], -1) == "в" ){
		$malePoints++;
	}
	if(mb_substr($parts['name'], -1) == "й" || mb_substr($parts['name'], -1) == "н"){
		$malePoints++;
	}
	if(mb_substr($parts['patronomyc'], -2) == "ич"){
		$malePoints++;
	}

	return gmp_sign($malePoints - $femalePoints);
}
function getGenderDescription ($personsArray){
	$manCount = 0;
	$womanCount = 0;
	$unknownCount = 0;
	$allPersons = count($personsArray);
	if ($allPersons==0) {
		return "Аудитория отсутствует";
	}
	foreach ($personsArray as $index => $person) {
		switch (getGenderFromName($person['fullname'])) {
			case 1:
				$manCount++;
				break;
			case -1:
				$womanCount++;
				break;
			case 0:
				$unknownCount++;
				break;
		}
	} 
	return "Гендерный состав аудитории:
---------------------------
Мужчины - ".round(100*$manCount/$allPersons, 1)."%
Женщины - ".round(100*$womanCount/$allPersons, 1)."%
Не удалось определить - ".round(100*$unknownCount/$allPersons, 1)."%";
}
function getPerfectPartner($surname, $name, $patronomyc, $personsArray){
	$surname = mb_strtoupper(mb_substr($surname, 0, 1)).mb_strtolower(mb_substr($surname, 1));
	$name = mb_strtoupper(mb_substr($name, 0, 1)).mb_strtolower(mb_substr($name, 1));
	$patronomyc = mb_strtoupper(mb_substr($patronomyc, 0, 1)).mb_strtolower(mb_substr($patronomyc, 1));
	$thisPerson = getFullnameFromParts($surname, $name, $patronomyc);
	$thisPersonGender = getGenderFromName($thisPerson);

	if($thisPersonGender==0){
		return "Нет совпадений";
	}

	$paraExist = 0;
	foreach ($personsArray as $index => $person) {
		if (getGenderFromName($person['fullname']) == -1 * $thisPersonGender) {
			$paraExist = 1;
			//проверяем есть ли люди противополодного пола
		}
	}

	if($paraExist==0){
		return "Нет совпадений";
	}

	while(true){
		$lovePerson = $personsArray[array_rand($personsArray)];
		if (getGenderFromName($lovePerson['fullname']) == -1 * $thisPersonGender) {
			break;
		}
	}
	return getShortName($thisPerson)." + ".getShortName($lovePerson['fullname'])." = 
♡ Идеально на ". (rand(0, 5000)/100)+50 ."% ♡";
}
$info = getPerfectPartner("пУШКина", "мАША", "безочетсва", $example_persons_array);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Идеальный подбор пары</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans" rel="stylesheet">
		<link rel="stylesheet" href="style.css" type="text/css"/>
	</head>
	<body>
		<div class="container">
			<div class="row game-card align-items-center">
				<div class="col col-md-8 offset-md-2">
					<div class="card text-center">
						<div class="card-header">
							<p class="m-0">Идеальный подбор пары</p>
						</div>
						<div class="table-body">
							<div class="row">
								<div class="col">
									<pre><?print_r($info);?></pre>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
