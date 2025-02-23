<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class Schedule extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'schedule';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = '\App\Entities\Schedule';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = ["day", "department_id", "shift_id", "user_id"];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = true;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	private $max = 0;
	private $maxState = null;
	private $maxWeeklyRuntime = 120;
	public $time = 0;

	/**
	 * Visszaadja az eddig előforduló hónapokat év-hó formában
	 * 
	 * @param $departmentId - részleg azonosító
	 * @param $order - lekérdezési sorrend dátum alapján
	 * 
	 * @return array
	 */
	public function getDates($departmentId, $order = "desc"){
		return $this->distinct()->select('DATE_FORMAT(day, "%Y-%m") as date')->orderBy("date", $order)->where("department_id", $departmentId)->findAll();
	}

	/**
	 * Visszaadja az a megadott részleg megadott hónapra elkészített beosztását
	 * 
	 * @param $departmentId - részleg azonoosítója
	 * @param $withJoin - csak az adatokat, vagy joinnal együtt a többi tábla adata is
	 * @param $dateYm - év-hónap, melyik havi beosztást adja vissza. Ha nincs megadva, akkor a jelenlegi hónap
	 * 
	 * @return array
	 */
	public function getItemsOneMonth($departmentId, $withJoin = false, $dateYm = null){
		$date = new \DateTime($dateYm);
		$from = $date->format("Y-m-01");
		//hozzáadunk 1 hónapot
		$to = (new \DateTime($from))->add(new \DateInterval("P1M"))->format("Y-m-d");
		if($withJoin){
			return $this->select('schedule.day, departments.id AS department_id, departments.name AS department_name,
			shifts.id AS shift_id, shifts.name AS shift_name,
			CONCAT(TIME_FORMAT(shifts.start_time,"%H:%i"),"-",TIME_FORMAT(shifts.end_time,"%H:%i")) AS shift_time,
			CONCAT_WS(" ",users.lastname," ",users.firstname) AS user_name, users.email AS user_email')
			->where("schedule.department_id", $departmentId)
			->where(["schedule.day >=" => $from, "schedule.day <" => $to])
			->join("departments", "schedule.department_id=departments.id", "left outer")
			->join("shifts", "schedule.shift_id=shifts.id", "left outer")
			->join("users", "schedule.user_id=users.id", "left outer")
			->orderBy("schedule.day", "asc")
			->findAll();
		}
		return $this->where("department_id", $departmentId)->where(["day >=" => $from, "day <" => $to])->orderBy("day", "asc")->findAll();
	}

	/**
	 * Elkészíti a beosztást
	 * @param $demands - kapacitás igények
	 * @param $restrictions - korlátozások
	 * @param $employees - munkavállalók
	 * @param $skills - a munkavállalók tudásai
	 * 
	 * @return array
	 */
	
	public function scheduling($demands, $restrictions, $employees, $skills){
		$state = $this->initState($demands, $restrictions, $employees, $skills);
		$state["demandId"] = 0;
		$state["count"] = 0;

		$weeks = [];
		foreach ($state["demands"] as $key => $d) {
			$w = (new \DateTime($d["day"]))->format("W") - $state["firstWeek"];
			$weeks[$w][] = $d;
		}

		$sche = [];
		foreach($weeks as $key => $week){		
			//deep copy, hogy ne referencia legyen
			$s = unserialize(serialize($state));
			$s["demands"] = $week;
			$this->maxState = null;
			$this->max = 0;
			$this->time = time();
			$this->scheduleBT($s, 0);
			$sche[] = $this->maxState;
		}

		$tmp = [];
		foreach ($sche as $s) {
			foreach ($s["demands"] as $key => $value) {
				if(!is_null($value["employee"])){
					$tmp[] = [
						"day" => $value["day"],
						"shift_id" => $value["shift_id"],
						"user_id" => $value["employee"]->id,
					];
				}
			}
		}

		return $tmp;
	}

	/**
	 * Kiválasztja és beállítja az optimális beosztást.
	 * @param $state - a jelenlegi állapot
	 * @param $demandId - korlátozás tömb kulcsa
	 * 
	 * @return null
	 */
	private function scheduleBT($state, $demandId){
		//ha túl sok ideig fut, leállítjuk, az addigi optimális eredmény marad
		if((time() - $this->time) > $this->maxWeeklyRuntime){
			return;
		}
		$count = 0;
		//megszámoljuk mennyi helyre kerültek már be
		foreach($state["demands"] as $key => $value){
			if(!is_null($value["employee"])){
				$count++;
			}
		}
		//ha jobb az új, azt az állapotot tároljuk
		if($this->max < $count) {
			$this->max = $count;
			$this->maxState = $state;
		}

		//ha már nincs több igény, kilépünk
		if($this->max == count($state["demands"])) {
			return;
		}

		if($demandId < count($state["demands"])) {
			$demand = $state["demands"][$demandId];	
			//lehetséges munkavállalók
			$e = $this->getEmployee($demand, $state);
			//ha nincs megfelelő munkavállaló, tovább a következő igényre
			if(!$e || count($e) == 0){
				$this->scheduleBT($state, $demandId+1);
			}else{
				if ($demandId < count($state["demands"])) {
					foreach ($e as $key => $emp) {
						//deep copy, hogy ne referencia legyen (objektumok miatt, mindenhol külön állapot kell)
						$s = unserialize(serialize($state));
						$s["demands"][$demandId]["employee"] = $emp;
						$s["employees"][$emp->id]->days[$demand["day"]] = $demand["shift_length"];
						$w = (new \DateTime($demand["day"]))->format("W") - $s["firstWeek"];
						$wh = $this->getWeekHours($emp, $w);
						$s["employees"][$emp->id]->weeks[$w] = $wh + $demand["shift_length"];
						//az új állapottal megyünk tovább
						$this->scheduleBT($s, $demandId+1);
					}
				}
			}
		}
	}

	/**
	 * Visszaadja az elérhető munkavállalókat a megadott igények és korlátozások alapján
	 * @param $demand - az igény ami alapján vizsgálunk
	 * @param $state - az initState() fv által elkészített tömb
	 * 
	 * @return mixed employee|null
	 */
	private function getEmployee($demand, &$state){
		$employees = $this->availableEmployees($state["employees"], $demand, $state["firstWeek"]);
		$_employees = [];
		foreach ($employees as $key => $value) {
			//naponta nullázódik a pont
			$employees[$key]->points = 0;

			$available = true;
			foreach($value->restrictions as $k => $restriction){
				//a szabadságnak muszály teljesülnie
				if($restriction["restriction_id"] == 7 && !$this->ruleDayOff($restriction, $demand)){
					$available = false;
					break;
				}

				//szeretne együtt dolgozni
				if($restriction["restriction_id"] == 9){
					$uId = $restriction["value"];
					if($this->worksToday($state["employees"][$uId], $demand["day"])){
						$result = false;
						foreach ($state["demands"] as $sd) {
							if($sd["day"] == $demand["day"]){
								if($sd["shift_id"] == $demand["shift_id"]){
									if(!is_null($sd["employee"]) && $sd["employee"]->id == $uId){
										$result = true;
										break;
									}
								}
							}
							if($sd["day"] > $demand["day"]){
								break;
							}
						}
						if($result){
							$employees[$key]->points += $restriction["bonus_point"] << 6;
						}
					}
				}
				// nem szeretne együtt dolgozni
				if($restriction["restriction_id"] == 6){
					$uId = $restriction["value"];
					if($this->worksToday($state["employees"][$uId], $demand["day"])){
						$result = false;
						foreach ($state["demands"] as $sd) {
							if($sd["day"] == $demand["day"]){
								if($sd["shift_id"] == $demand["shift_id"]){
									if(!is_null($sd["employee"]) && $sd["employee"]->id == $uId){
										$result = true;
										break;
									}
								}
							}
							if($sd["day"] > $demand["day"]){
								break;
							}
						}
						if($result){
							$employees[$key]->points -= $restriction["bonus_point"] << 6;
						}
					}
				}

				//a hét ezen a napján nem szeretne dolgozni
				if($restriction["restriction_id"] == 8){
					$date = new \DateTime($demand["day"]);
					if($restriction["value_type"] == "all"){
						if($date->format("N") == $restriction["value"]){
							$employees[$key]->points += $restriction["bonus_point"] << 4;
						}
					}elseif($restriction["value_type"] == "given_week"){
						//ha megegyezik a hét és a nap sorszáma
						if($date->format("W") == $restriction["week_number"] && $date->format("N") == $restriction["value"]){
							$employees[$key]->points += $restriction["bonus_point"] << 4;
						}
					}
				}

				//ebben a műszakban szeretne dolgozni
				if($restriction["restriction_id"] == 1){
					//egész hónap
					if($restriction["value_type"] == "all"){
						if($restriction["value"] == $demand["shift_id"]){
							$employees[$key]->points += $restriction["bonus_point"] << 4;
						}
					//adott hét
					}elseif($restriction["value_type"] == "given_week"){
						$date = new \DateTime($demand["day"]);
						if($date->format("W") == $restriction["week_number"] && $restriction["value"] == $demand["shift_id"]){
							$employees[$key]->points += $restriction["bonus_point"] << 4;
						}
					//adott nap
					}else{
						if($restriction["day_value"] == $demand["day"] && $restriction["value"] == $demand["shift_id"]){
							$employees[$key]->points += $restriction["bonus_point"] << 4;
						}
					}
				}

				//ebben a műszakban nem szeretne dolgozni
				if($restriction["restriction_id"] == 2){
					//egész hónap
					if($restriction["value_type"] == "all"){
						if($restriction["value"] == $demand["shift_id"]){
							$employees[$key]->points -= $restriction["bonus_point"] << 4;
						}
					//adott hét
					}elseif($restriction["value_type"] == "given_week"){
						$date = new \DateTime($demand["day"]);
						if($date->format("W") == $restriction["week_number"] && $restriction["value"] == $demand["shift_id"]){
							$employees[$key]->points -= $restriction["bonus_point"] << 4;
						}
					//adott nap
					}else{
						if($restriction["day_value"] == $demand["day"] && $restriction["value"] == $demand["shift_id"]){
							$employees[$key]->points -= $restriction["bonus_point"] << 4;
						}
					}
				}

				//heti munkanapok száma
				if($restriction["restriction_id"] == 3){
					if($restriction["value_type"] == "all"){
						if($this->getWorkDays($value, $demand["day"]) < $restriction["value"]){
							$employees[$key]->points += $restriction["bonus_point"] << 3;
						}
					}elseif($restriction["value_type"] == "given_week"){
						if((new \DateTime($demand["day"]))->format("W") == $restriction["week_number"]){
							if($this->getWorkDays($value, $demand["day"]) < $restriction["value"]){
								$employees[$key]->points += $restriction["bonus_point"] << 3;
							}
						}
					}
				}

				//heti egymás utáni munkanapok száma
				if($restriction["restriction_id"] == 4){
					if($this->getContinousDays($value, $demand["day"]) <= $restriction["value"]){
						$employees[$key]->points += $restriction["bonus_point"] << 2;
					}elseif($restriction["value_type"] == "given_week"){
						if((new \DateTime($demand["day"]))->format("W") == $restriction["week_number"]){
							if($this->getContinousDays($value, $demand["day"]) <= $restriction["value"]){
								$employees[$key]->points += $restriction["bonus_point"] << 2;
							}
						}
					}
				}

				//heti egymás utáni szabadnapok száma
				if($restriction["restriction_id"] == 5){
					if($this->getContinousFreeDays($value, $demand["day"]) >= $restriction["value"]){
						$employees[$key]->points += $restriction["bonus_point"] << 2;
					}elseif($restriction["value_type"] == "given_week"){
						if((new \DateTime($demand["day"]))->format("W") == $restriction["week_number"]){
							if($this->getContinousFreeDays($value, $demand["day"]) >= $restriction["value"]){
								$employees[$key]->points += $restriction["bonus_point"] << 2;
							}
						}
					}
				}
			}

			$start = $state["demands"][0]["day"];
			$day = $demand["day"];
			$employees[$key]->points += (40 - $this->totalWorkHours($value, $start, $day));

			if($available){
				$_employees[] = clone $employees[$key];
			}
		}
		
		usort($_employees, function($a, $b){			
			if ($a->points == $b->points) {
				return 0;
			}
			return ($a->points < $b->points) ? 1 : -1;
		});
		
		if(!empty($_employees)){
			return $_employees;
		}
		return null;
	}

	/**
	 * Visszaadja, hogy mennyit dolgozott már a munkavállaló az adott héten
	 * 
	 * @param $employee - munkavállaló
	 * @param $week - a hét sorszáma
	 * 
	 * @return int
	 */
	private function getWeekHours($employee, $week){
		if(array_key_exists($week, $employee->weeks)){
			return $employee->weeks[$week];
		}
		return 0;
	}

	/**
	 * A héten mennyi napot dolgozott
	 */
	private function getWorkDays($employee, $day){
		$c = 0;
		for ($i=0; $i < 7; $i++) {
			$first = (new \DateTime($day))->modify("Monday this week");
			$d = $first->add(new \DateInterval("P{$i}D"))->format("Y-m-d");
			if($this->worksToday($employee, $d)){
				$c++;
			}
		}
		return $c;
	}

	private function totalWorkHours($employee, $start, $day){
		$s = new DateTime($start);
		$c = 0;
		while ($s->format("Y-m-d") <= $day) {
			if($this->worksToday($employee, $s->format("Y-m-d"))){
				$c += $employee->days[$s->format("Y-m-d")];
			}
			$s->add(new \DateInterval("P1D"));
		}
		return $c;
	}

	/**
	 * Egymás utáni munkanapok
	 */
	private function getContinousDays($employee, $day){
		$first = (new \DateTime($day))->modify("Monday this week");
		$d = (new \DateTime($day))->sub(new \DateInterval("P1D"));
		$c = 0;
		while ($d->format("y-m-d") >= $first->format("Y-m-d")) {
			if($this->worksToday($employee, $d->format("y-m-d"))){
				$c++;
			}else{
				return $c;
			}
			$d->sub(new \DateInterval("P1D"));
		}
		return $c;
	}

	/**
	 * Egymás utáni szabadnapok
	 */
	private function getContinousFreeDays($employee, $day){
		$first = (new \DateTime($day))->modify("Monday this week");
		$d = (new \DateTime($day))->sub(new \DateInterval("P1D"));
		$c = 0;
		while ($d->format("y-m-d") >= $first->format("Y-m-d")) {
			if(!$this->worksToday($employee, $d->format("y-m-d"))){
				$c++;
			}else{
				return $c;
			}
			$d->sub(new \DateInterval("P1D"));
		}
		return $c;
	}

	/**
	 * Megvizsgálja, hogy a megadott munkavállaló már be van-e osztva a megadott napra
	 * @param $employee - munkavállaló
	 * @param $day - a nap amelyiket vizsgálni kell
	 * 
	 * @return bool
	 */
	private function worksToday($employee, $day){
		if(array_key_exists($day, $employee->days)){
			return $employee->days[$day] > 0;
		}
		return false;
	}

	/**
	 * Összeállítja a szükséges tömböt a kapott adatokból
	 * @param $demands - kapacitás igények
	 * @param $restrictions - korlátozások
	 * @param $employees - munkavállalók
	 * @param $skills - a munkavállalók tudásai
	 * 
	 * @return array
	 */
	private function initState($demands, $restrictions, $employees, $skills){
		$_employees = [];
		foreach ($employees as $e) {
			$_employees[$e["id"]] = (object)[
				"id" => $e["id"],
				"skills" => [],
				"restrictions" => [],
				"hours" => $e["weekly_work_hours"],
				"days" => [],
				"weeks" => [],
				"points" => 0
			];
		}
		foreach ($restrictions as $value) {
			$_employees[$value["user_id"]]->restrictions[] = $value;
		}
		foreach ($skills as $value) {
			$_employees[$value["user_id"]]->skills[] = $value;
		}

		$_demands = [];
		foreach ($demands as $key => $value) {
			for ($i=0; $i < $value->amount; $i++) {
				if($value->shift_start < $value->shift_end){
					$value->shift_length = ((new \DateTime($value->shift_start))->diff((new \DateTime($value->shift_end))))->h;
				}else{
					$value->shift_length = ((new \DateTime($value->shift_start))->diff((new \DateTime($value->shift_end))->add(new \DateInterval("P1D"))))->h;
				}
				$_demands[] = [
					"day" => $value->day,
					"shift_id" => $value->cd_shift_id,
					"knowledge_id" => $value->cd_knowledge_id,
					"knowledge_level_id" => $value->cd_knowledge_level_id,
					"shift_length" => $value->shift_length,
					"amount" => 1,
					"key" => $key,
					"employee" => null
				];
			}
		}

		return [
			"employees" => $_employees,
			"demands" => $_demands,
			"firstWeek" => (new \DateTime($_demands[0]["day"]))->format("W")
		];
	}

	/**
	 * Visszaadja az elérhető munkavállalókat, akik eleget tesznek a feltételeknek
	 * 
	 * @param $employees - munkavállalók
	 * @param $demand - az igény ami alapján vizsgálunk
	 * @param $firstWeek - a beosztás első napja melyik hétre esik
	 * 
	 * @return array
	 */
	private function availableEmployees($employees, $demand, $firstWeek){
		$_employees = [];
		$week = (new \DateTime($demand["day"]))->format("W") - $firstWeek;
		foreach($employees as $key => $e){
			//ha nincs még beosztva az adott napon
			if(!$this->worksToday($e, $demand["day"])){
				//ha megvan a szükséges tudása
				if($this->ruleKnowledge($e, $demand)){
					//még dolgozhat annyit az adott héten
					if(($this->getWeekHours($e, $week) + $demand["shift_length"]) <= $e->hours){
						$_employees[] = $employees[$key];
					}
				}
			}
		}
		return $_employees;
	}

	/**
	 * Megvizsgálja hogy van-e megfelelő tudása a munkavállalónak
	 * @param $employee - amunkavállaló
	 * @param $demand - az igény ami alapján vizsgálunk
	 * 
	 * @return bool
	 */
	private function ruleKnowledge($employee, $demand){
		foreach ($employee->skills as $s => $skill) {
			if($skill["knowledge_id"] === $demand["knowledge_id"] && $skill["knowledge_level_id"] >= $demand["knowledge_level_id"]){
				return true;
			}
		}
		return false;
	}

	/**
	 * Ezen a napon szabadságon vagyok
	 */
	private function ruleDayOff($restriction, $demand){
		return $restriction["value"] !== $demand["day"];
	}
}
