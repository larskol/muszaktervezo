<?php
namespace App\Validation;
class CustomRules
{
    public function even(string $str): bool
    {
        return (int)$str % 2 == 0;
    }

    /**
     * Ellenőrzi hogy a felhasználó hozzáadta-e már az adott ismeretet
     */
    public function skill_not_exists(string $str, string $userId, array $data) : bool
    {
        $model = new \App\Models\Skill();
        return $model->select("id")->where(["user_id" => $userId, "knowledge_id" => $str])->countAllResults() == 0;
    }

    /**
     * A departments tömb csak számokat és létező részleget tartalmazhat
     */
    public function allowed_department($str){
        $request = service("request");
        $data = $request->getPost("departments");
        if(is_null($data)){
            return true;
        }
        if(!is_array($data)){
            return false;
        }
        foreach($data as $item){
            if(!is_numeric($item)) return false;
        }

        $department = new \App\Models\Department();
        $idArray = array_column($department->select("id")->whereIn("id", $data)->findAll(), "id");

        $return = true;
        //megnézzük benne van e minden érték az engedélyezettek között
        foreach($data as $value){
            $return &= in_array($value, $idArray);
        }

        return $return == 1;
    }

    /**
     * Ellenőrzi hogy a a megadott érték engedélyezett-e a részleg adminisztrátor számára
     */
    public function is_allowed_item(string $id, string $type, array $data) : bool
    {
        $session = session();
        if($type == "knowledge"){
            $model = new \App\Models\Knowledge();
            $departmentId = $session->get("userCurrentDepartmentId");
            return !is_null($model->select("id")->where("JSON_CONTAINS(departments,'[\"{$departmentId}\"]') > ", 0)->find($id));
        }elseif($type == "shift"){
            $model = new \App\Models\Shift();
            $departmentId = $session->get("userCurrentDepartmentId");
            return !is_null($model->select("id")->where("JSON_CONTAINS(departments,'[\"{$departmentId}\"]') > ", 0)->find($id));
        }
        return false;
    }

    /**
     * Ellenőrzi hogy a a megadott érték engedélyezett-e a felhasználó számára
     */
    public function is_allowed_item_user(string $id, string $type, array $data) : bool
    {
        $session = session();
        if($type == "knowledge"){
            $model = new \App\Models\Knowledge();
            $departmentId = $session->get("userDepartment");
            return !is_null($model->select("id")->where("JSON_CONTAINS(departments,'[\"{$departmentId}\"]') > ", 0)->find($id));
        }elseif($type == "shift"){
            $model = new \App\Models\Shift();
            $departmentId = $session->get("userDepartment");
            return !is_null($model->select("id")->where("JSON_CONTAINS(departments,'[\"{$departmentId}\"]') > ", 0)->find($id));
        }
        return false;
    }

    /**
     * Ellenőrzi hogy a megadott dátum megfelelő-e, csak következő havi lehet
     */
    public function next_month_date(string $str) : bool
    {
        $date = (new \DateTime())->add(new \DateInterval("P1M"));
        return ($str >= $date->format("Y-m-01") && $str <= $date->modify('last day of this month')->format("Y-m-d"));
    }
}