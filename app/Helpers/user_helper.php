<?php
/**
 * Üdvözlő üzenet a bejelentkezett felhasználónak
 */
if(!function_exists('get_user_login_info_text')){
    function get_user_login_info_text(){
        $session = session();
        return lang("Site.siteUserLoginInfo", [$session->get("userName"), $session->get("userDepartmentName"), ""]);
    }
}

/**
 * Üdvözlő üzenet a bejelentkezett részleg adminnak
 */
if(!function_exists('get_department_admin_login_info_text')){
    function get_department_admin_login_info_text(){
        $session = session();
        return lang("Site.siteUserLoginInfo", [$session->get("userName"), $session->get("userDepartmentName"), lang("Site.siteRole_".$session->get("userRole"))]);
    }
}

/**
 * Üdvözlő üzenet a bejelentkezett adminnak
 */
if(!function_exists('get_admin_login_info_text')){
    function get_admin_login_info_text(){
        $session = session();
        return lang("Site.siteUserLoginInfo", [$session->get("userName"), $session->get("userDepartmentName"), lang("Site.siteRole_".$session->get("userRole"))]);
    }
}

/**
 * A részleg admin számára elérhető részlegek (egy view string, ami tartalmaz egy select mezőt)
 * 
 * @return string
 */
if(!function_exists('get_department_select_fields')){
    function get_department_select_fields(){
        $session = session();
        $model = new \App\Models\Department();
        $content["departments"] = $model->find($session->get("userDepartments"));
        $content["current"] = $session->get("userCurrentDepartmentId");
        return view("users/department/department_select", $content);
    }
}