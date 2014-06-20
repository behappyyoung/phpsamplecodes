<?php

class Yummly{
    var $api_id = 'a09b3eef';//'b18063cf';
    var $app_key = 'e22f0c2ccf4f8e4d4a5e88ed47ebc414';//'3d78eed8f06fc7965792aaf2b690beb9';
    var $limit = 20;

    function searchRecipe($q,$p, $limit = 20){
        $start = $this->limit*($p);
        $start_time = time();
        $q = urlencode($q);
        $q = "http://api.yummly.com/v1/api/recipes?q=$q&_app_id={$this->api_id}&_app_key={$this->app_key}&maxResult={$limit}&start={$start}";
        $total_time = time()-$start_time;
        $recipes = file_get_contents($q);
        $recipes = json_decode($recipes,true);
        $recipes['api_time'] = $total_time;
        return $recipes;
    }


    function getRecipe($id){
        $start = time();
        $db = new db();
        $result = $db->select("select * from shn_yummly_recipes where yummly_id like '$id' ");
        //print_r($result);exit();
        if( !empty($result[0]['recipe_json']) ){
            $recipe = $result[0]['recipe_json'];
            $recipe = json_decode($recipe,true);
            $recipe_id = $recipe['shn_recipe_id'] = $result[0]['id'];
            $r['last_read'] = $db->mySQLSafe(date('Y-m-d H:i:s'));
            $db->update('shn_yummly_recipes',$r,"id={$recipe_id}");
        }else{
            $q = "http://api.yummly.com/v1/api/recipe/{$id}?&_app_id={$this->api_id}&_app_key={$this->app_key}";
            $total_time = time()-$start;
            $recipe_json = file_get_contents($q);
            $recipe = json_decode($recipe_json,true);

            $r['recipe_json'] = $db->mySQLSafe($recipe_json);
            $r['last_read'] = $db->mySQLSafe(date('Y-m-d H:i:s'));
            $r['yummly_id'] = $db->mySQLSafe($id);
            $r['name'] = $db->mySQLSafe($recipe['name']);
            if( empty($result[0]['id']) ){
                $db->insert('shn_yummly_recipes',$r);
            }else{
                $recipe_id = $result[0]['id'];
                $db->update('shn_yummly_recipes',$r,"id={$recipe_id}");
            }
            $recipe['shn_recipe_id']  = $db->insertid();
        }
        $recipe['api_time'] = $total_time;
        return $recipe;
    }
}
