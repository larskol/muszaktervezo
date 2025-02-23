<?php

if(!function_exists('get_user_selected_restriction_item_data')){
    function get_user_selected_restriction_item_data($userRestriction){
        $viewContent = [
            "name" => $userRestriction->name,
            "value" => $userRestriction->value,
            "cost" => $userRestriction->bonus_point,
            "id" => $userRestriction->id,
        ];

        switch ($userRestriction->type) {
            case 'users':
                $user = new \App\Models\User();
                $item = $user->find($userRestriction->value);
                $viewContent["value_text"] = isset($item) ? "{$item->lastname} {$item->firstname} ({$item->email})" : lang("Site.siteDeletedData");
                break;
            case 'shifts':
                $shift = new \App\Models\Shift();
                $item = $shift->find($userRestriction->value);
                $viewContent["value_text"] = isset($item, $item->name) ? $item->name : lang("Site.siteDeletedData");
                $viewContent["value_text"] .= user_restriction_additional_info($userRestriction);
                break;
            case 'custom_days':
                $viewContent["value_text"] = lang("Site.siteDay{$userRestriction->value}");
                $viewContent["value_text"] .= user_restriction_additional_info($userRestriction);
                break;
            default:
                $viewContent["value_text"] = $userRestriction->value;
                if($userRestriction->type == "input"){
                    $viewContent["value_text"] .= user_restriction_additional_info($userRestriction);
                }
                break;
        }
        return $viewContent;
    }
}

if(!function_exists('user_restriction_additional_info')){
function user_restriction_additional_info($userRestriction){
    if($userRestriction->value_type == "given_week"){
        return " (".$userRestriction->week_number." ".lang("Site.siteNthWeek").")";
    }elseif($userRestriction->value_type == "all"){
        return " (".lang("Site.siteAllMonth").")";
    }
    elseif($userRestriction->value_type == "given_day"){
        return " (".$userRestriction->day_value.")";
    }
    return "";
}
}