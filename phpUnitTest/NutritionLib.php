<?php
/**
 * Created by JetBrains PhpStorm.
 * User: imran
 * Date: 2/24/14
 * Time: 2:16 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('db/config.inc.php');
require_once('Yummly.php');
class NutritionLib {
    private $meal_types = array(
        1 => 'Breakfast',
        2 => 'Lunch',
        3 => 'Dinner',
        4 => 'Snack'
    );
    function __construct(){
        $this->db = new db();
    }


    function build_meal( $meal ){
        $yummlyObj = new Yummly();
        $images = array();
        $yummly_nut_map = array(
            'ENERC_KJ' => 'calories' ,
            'FAT' => 'fat',
            'PROCNT' => 'protein'//,
            //'calories' => 'ENERC_KJ'
        );
        $nutrients = array(
            'calories' => 0,
            'carb' => 0,
            'fat' => 0,
            'protein' => 0
        );
        $meal_items = array();
        $totaltime = 0;
        foreach($meal['items'] as $item){
            if( !is_numeric($item) ){
                $recipe = $yummlyObj->getRecipe($item);
                //print_r($recipe);exit();
                $images[] = array(
                    'source' => 'yummly',
                    'url' => $recipe['images'][0]['hostedLargeUrl']
                );
                $meal_item = array();
                $meal_item['name'] = $this->db->mySQLSafe($recipe['name']);
                $meal_item['item_type'] = $this->db->mySQLSafe('recipe');
                $meal_item['item_id'] = $recipe['shn_recipe_id'];
                $meal_item['servings'] = $recipe['numberOfServings'];

                $totaltime += round($recipe['totalTimeInSeconds']/60);

                foreach($recipe['nutritionEstimates'] as $nut){
                    $nk = isset($yummly_nut_map[ $nut['attribute'] ])  ? $yummly_nut_map[ $nut['attribute'] ] : null;
                    if( $nk ){
                        $nutrients[ $nk ] += $nut['value'];
                    }
                }
                $meal_item['nutrients'] = $this->db->mySQLSafe(json_encode($nutrients));
                $meal_items[] = $meal_item;
            }else{
                $result = $this->db->select("select * from shn_foods where id={$item}");
                if( empty($result) ){ continue; }
                $food = $result[0];
                $meal_item = array();

                $food['weights'] = json_decode($food['weights'],true);
                $s = $food['weights'][ rand(0, count($food['weights'])-1 ) ];
                $meal_item['servings'] = $s['serving'];
                $meal_item['serving_unit'] = $this->db->mySQLSafe($s['unit']);
                $meal_item['name'] = $this->db->mySQLSafe($food['name']);
                $meal_item['item_type'] = $this->db->mySQLSafe('food');
                $meal_item['item_id'] = $food['id'];
                $food['weights'] = $this->db->mySQLSafe($food['weights']);

                $meal_items[] = $meal_item;
            }
        }


        $m = array();
        $m['name'] = $this->db->mySQLSafe($meal['name']);
        $m['meal_types'] = 15;
        $m['servings'] = 1;
        $m['prep_time'] = $totaltime;
        $m['images'] = $this->db->mySQLSafe( json_encode($images));
        $m['nutrients'] = $this->db->mySQLSafe(json_encode($nutrients));
        //print_r($m);exit();
        $this->db->insert('shn_meals',$m);
        $shn_meal_id = $this->db->insertid();
        foreach($meal_items as $item){
            $item['shn_meal_id'] = $shn_meal_id;
            $this->db->insert('shn_meal_items',$item);
        }

        return $shn_meal_id;
    }

    function getMealsByTrack( $track_id, $limit = 5){
        $query = "SELECT MealPlan.meals FROM shn_tracks AS Track
            LEFT JOIN shn_meal_plans MealPlan ON Track.shn_meal_plan_id = MealPlan.id
            WHERE Track.id = {$track_id}";
        $meals = $this->db->select($query);
        if( empty($meals[0]['meals']) ){ return array(); }
        $meals = json_decode($meals[0]['meals'], true);
        if( empty($meals) ){ return array(); }
        $meals_in_query = implode(',',$meals);
        $query  = "SELECT * FROM shn_meals Meals WHERE Meals.id IN ($meals_in_query) ORDER BY Meals.id DESC LIMIT {$limit}";
        $meals = $this->db->select($query);
        foreach($meals as $k=>$meal){
            $meals[$k]['nutrients'] = json_decode($meal['nutrients'],true);
            $meals[$k]['images'] = json_decode($meal['images'],true);
            $query = "SELECT * FROM shn_meal_items MealItem
            WHERE MealItem.shn_meal_id = {$meal['id']}";
            $meal_items = $this->db->select($query);
            foreach($meal_items as $i=>$item){
                $meal_items[$i]['nutrients'] = json_decode($item['nutrients'],true);
            }
            $meals[$k]['MealItem'] = $meal_items;
        }

       return $meals;
    }
    
    function getMealDetail( $meal_id=1 ){
        $meals = '';
        $query  = "SELECT * FROM shn_meals Meals WHERE Meals.id=" . $meal_id;
        $meals = $this->db->select($query);
        if($meals && count($meals)>0){
            foreach($meals as $k=>$meal){
                $meals[$k]['name']       = $meal['name'];
                $meals[$k]['directions'] = $meal['directions'];
                $meals[$k]['prep_time']  = $meal['prep_time'];

                $meals[$k]['mealtypes'] = $meal['meal_types'];
                $mt = $meal['meal_types'];
                $meal_type_arr = $this->getMealTypes();
                unset($mts);
                $mts = array();
                for($x=1;$x<5;$x++){
                    if($mt & pow(2,$x-1)){
                        $mts[] = $meal_type_arr[$x];
                    }   
                }
                $meals[$k]['meal_types'] = implode(",",$mts);
                    
                $meals[$k]['nutrients'] = json_decode($meal['nutrients'],true);
                $meals[$k]['images'] = json_decode($meal['images'],true);
                $query = "SELECT * FROM shn_meal_items MealItem
                WHERE MealItem.shn_meal_id = {$meal['id']}";
                $meal_items = $this->db->select($query);
                if($meal_items && count($meal_items)>0){
                    foreach($meal_items as $i=>$item){
                        $meal_items[$i]['nutrients'] = json_decode($item['nutrients'],true);
                    }
                    $meals[$k]['MealItem'] = $meal_items;
                }
            }
        }

       return $meals;
    }

    function getMealItemDetail( $meal_item_id=1 ){
        $meals = '';
        $query  = "SELECT * FROM shn_meal_items MIs WHERE MIs.id=" . $meal_item_id;
        $meals = $this->db->select($query);
        if($meals && count($meals)>0){
            foreach($meals as $k=>$meal){
                $meals[$k]['name']    	  = $meal['name'];
                $meals[$k]['item_type'] 	 = $meal['item_type'];
				$meals[$k]['servings'] 	  = $meal['servings'];
                $meals[$k]['serving_unit']  = $meal['serving_unit'];
                $meals[$k]['nutrients']  	 = json_decode($meal['nutrients']);
            }
        }

       return $meals;
    }

    function getMealTypes(){
        return $this->meal_types;
    }

    function addMeal( $id = null, $name, $directions, $meal_types, $prep_time){
        $data = array();
        $data['name'] = $this->db->mySQLSafe($name);
        $data['directions'] = $this->db->mySQLSafe($directions);

        $data['meal_types'] = 0;
        foreach($meal_types as $v){
            $data['meal_types'] += pow(2,$v-1);
        }

        $data['prep_time'] = $this->db->mySQLSafe($prep_time);

        if( $id == null ){
            $this->db->insert('shn_meals',$data);
            return $this->db->insertid();
        }else{
            $this->db->update('shn_meals',$data, "id=$id");
            return $id;
        }
    }

    function getFood( $id ){
        $data = $this->db->select("SELECT * FROM shn_foods WHERE id=$id");
        if( empty($data) ){ return false; }

        $food = $data[0];
        $food['nutrients'] = json_decode($food['nutrients'],true);
        $food['weights'] = json_decode($food['weights'],true);
        return $food;
    }
    function addMealItem( $meal_item_id = null, $meal_id, $type, $item_id, $name = null, $servings, $serving_unit){
        if( $type == 'food' ){
            $this->addFoodToMeal($meal_item_id,$meal_id, $item_id, $servings, $serving_unit);
        }else if( $type == 'recipe' ){
            $this->addRecipeToMeal($meal_item_id,$meal_id, $item_id, $servings);
        }else if( $type == 'custom' ){
            $this->addCustomFoodToMeal($meal_item_id,$meal_id, $name, $servings, $serving_unit);
        }
    }

/*    function addMealItem( $meal_item_id = null, $meal_id, $type, $item_id, $name = null, $servings, $serving_unit, $nutrients){
        if( $type == 'food' ){
            $this->addFoodToMeal($meal_item_id,$meal_id, $item_id, $servings, $serving_unit);
        }else if( $type == 'recipe' ){
            $this->addRecipeToMeal($meal_item_id,$meal_id, $item_id, $servings);
        }else if( $type == 'custom' ){
            $this->addCustomFoodToMeal($meal_item_id,$meal_id, $name, $servings, $serving_unit, $nutrients);
        }
    }
*/
    function addCustomFoodToMeal(  $meal_item_id = null, $meal_id, $name, $servings, $serving_unit  ){

        $meal_item = array();

        $meal_item['name'] = $this->db->mySQLSafe($name);
        $meal_item['shn_meal_id'] = $this->db->mySQLSafe($meal_id);
        $meal_item['item_type'] = $this->db->mySQLSafe('free_entry');
        $meal_item['servings'] = $this->db->mySQLSafe($servings);
        $meal_item['serving_unit'] = $this->db->mySQLSafe($serving_unit);

        if( $meal_item_id ){
            $this->db->update('shn_meal_items',$meal_item,"id={$meal_item_id}");
            $this->updateMealNutrients($meal_id);
            return $meal_item_id;
        }else{
            $this->db->insert('shn_meal_items',$meal_item);
            $this->updateMealNutrients($meal_id);
            return $this->db->insertid();
        }
    }

    function addFoodToMeal(  $meal_item_id = null, $meal_id, $food_id, $servings, $serving_unit ){
        $food = $this->getFood($food_id);
        $meal_item = array();
        $weights = $food['weights'];
        $gm = 0;
        foreach($weights as $w){
            if( strtolower($w['unit']) == strtolower($serving_unit)  ){
                $gm = round($w['weight']/$w['serving']*$servings,1);
            }

        }
        $nutrients = array();
        foreach($food['nutrients'] as $k=>$v){
            $v['value'] = round($v['value']/100*$gm,1);
            $nutrients[$k] = $v;
        }
        $meal_item['nutrients'] = $this->db->mySQLSafe( json_encode($nutrients) );
        $meal_item['name'] = $this->db->mySQLSafe($food['name']);
        $meal_item['shn_meal_id'] = $this->db->mySQLSafe($meal_id);
        $meal_item['item_type'] = $this->db->mySQLSafe('food');
        $meal_item['item_id'] = $this->db->mySQLSafe($food_id);
        $meal_item['servings'] = $this->db->mySQLSafe($servings);
        $meal_item['serving_unit'] = $this->db->mySQLSafe($serving_unit);

        if( $meal_item_id ){
            $this->db->update('shn_meal_items',$meal_item,"id={$meal_item_id}");
            $this->updateMealNutrients($meal_id);
            return $meal_item_id;
        }else{
            $this->db->insert('shn_meal_items',$meal_item);
            $this->updateMealNutrients($meal_id);
            return $this->db->insertid();
        }
    }

    function addRecipeToMeal($meal_item_id = null, $meal_id, $recipe_id, $servings ){
        $recipe = $this->getRecipe($recipe_id);

        $meal_item = array();

        $meal_item['name'] = $this->db->mySQLSafe($recipe['name']);
        $meal_item['shn_meal_id'] = $meal_id;
        $meal_item['item_type'] = $this->db->mySQLSafe('recipe');
        $meal_item['item_id'] = $recipe['id'];
        $meal_item['servings'] = $servings;
        $meal_item['serving_unit'] = $this->db->mySQLSafe('servings');

        $nutrients = array();
        foreach($recipe['nutrients'] as $k=>$v){
			$rec_servings = $recipe['servings'];
			if(!is_nan($rec_servings)){
				$rec_servings = 1;
			}

            $nutrients[$k] = array(
                'nut_id' => null,
                'value' => round($v['value']/$rec_servings*$servings,1)
            );
        }

        $meal_item['nutrients'] = $this->db->mySQLSafe( json_encode($nutrients) );

        if( $meal_item_id ){
            $this->db->update('shn_meal_items',$meal_item,"id={$meal_item_id}");
            $this->updateMealNutrients($meal_id);
            return $meal_item_id;
        }else{
            $this->db->insert('shn_meal_items',$meal_item);
            $this->updateMealNutrients($meal_id);
            return $this->db->insertid();
        }
    }

    function updateMealNutrients( $meal_id ){
        $query = "SELECT nutrients FROM shn_meal_items MealItem WHERE shn_meal_id=$meal_id";
        $nutrients = array();
        $items = $this->db->select($query);
        foreach($items as $item){
            $item['nutrients'] = json_decode($item['nutrients'],true);
            foreach($item['nutrients'] as $k=>$v){
                if( !isset($nutrients[$k]) ) { $nutrients[$k] = array('nut_id' => $v['nut_id'],'value' => 0); }
                $nutrients[$k]['value'] += $v['value'];
            }
        }
        $data['nutrients'] = $this->db->mySQLSafe(json_encode($nutrients));
        $this->db->update('shn_meals',$data,"id={$meal_id}");
        return true;
    }

    function filterKeywords($kw){
        $kw = str_replace(" and "," ",$kw);
        $kw = str_replace(" or "," ",$kw);
        $kw = str_replace(" the "," ",$kw);
        $kw = str_replace(" in "," ",$kw);
        $kw = str_replace(" on "," ",$kw);
        $kw = str_replace(" at "," ",$kw);
        $kw = str_replace(" if "," ",$kw);
        $kw = str_replace(" with "," ",$kw);
        return $kw;
    }

    function getMealItem( $meal_id ){
        $meals = '';
        $query  = "SELECT * FROM shn_meal_items SMs WHERE SMs.shn_meal_id = " . $this->db->mySQLSafe($meal_id);

        $meal = $this->db->select($query);
        if($meal && count($meal)>0){
            foreach($meal as $k=>$ml){
                $meals[$k]['id']           = $ml['id'];
                $meals[$k]['name']       = $ml['name'];
			    $meals[$k]['item_id']      = $ml['item_id'];
                $meals[$k]['item_type']    = $ml['item_type'];
                $meals[$k]['servings']     = $ml['servings'];
                $meals[$k]['serving_unit'] = $ml['serving_unit'];
                $meals[$k]['nutrients'] = json_decode($ml['nutrients'],true);
            }
        }

        return $meals;
    }

    function searchMeal( $kw="", $meal_types=null,$page_no=0,$limit=20){
        $meals = '';
        $kw = $this->filterKeywords($kw);
        $kw_arr = explode(" ",$kw);
        $query  = "SELECT * FROM shn_meals Meals WHERE deleted=0";
/*      if($kw!=''){
            $query .= " WHERE LOWER(Meals.name) LIKE " . $this->db->mySQLSafe("%" . strtolower($kw) . "%");             
        }*/
        if($kw_arr && count($kw_arr)>0){
            for($z=0;$z<count($kw_arr);$z++){
                if($kw_arr[$z]!=''){
                    if($z==0){
                        $query .= " AND LOWER(Meals.name) LIKE " . $this->db->mySQLSafe("%" . strtolower($kw_arr[$z]) . "%"); 
                    }else{
                        $query .= " OR LOWER(Meals.name) LIKE " . $this->db->mySQLSafe("%" . strtolower($kw_arr[$z]) . "%");    
                    }
                }
            }
        }
        if($meal_types!=null){
            $mt_mask = 0;
            foreach($meal_types as $tmp_v){
                $mt_mask += pow(2,$tmp_v-1);
            }
            $query  .=  " AND meal_types & {$mt_mask}";
        }
        
        $meal = $this->db->select($query,$limit,$page_no);
        $numRows = $this->db->numrows($query);
        if($meal && count($meal)>0){
            foreach($meal as $k=>$ml){
                $meals[$k]['id']        = $ml['id'];
                $meals[$k]['name']    = $ml['name'];
                $meals[$k]['servings']     = $ml['servings'];
                $meals[$k]['serving_unit'] = $ml['serving_unit'];
                $mt = $ml['meal_types'];
                $meal_type_arr = $this->getMealTypes();
                unset($mts);
                $mts = array();
                for($x=1;$x<5;$x++){
                    if($mt & pow(2,$x-1)){
                        $mts[] = $meal_type_arr[$x];
                    }   
                }
                $meals[$k]['meal_types'] = implode(", ",$mts);
                $meals[$k]['nutrients'] = json_decode($ml['nutrients'],true);
            }
        }
        $meals_ret['meals']      = $meals;
        $meals_ret['pagination'] = $this->db->paginate($numRows, $limit, $page_no, elgg_get_site_url() . 'meal_plans/meals',"&q=" . $_GET['q']);
        return $meals_ret;
    }

    function removeMealItem( $meal_item_id ){
        $result = $this->db->select("SElECT shn_meal_id FROM shn_meal_items WHERE id={$meal_item_id}");
        if( !empty($result) ){
            $meal_id = $result[0]['shn_meal_id'];
        }
        $this->db->delete('shn_meal_items',"id={$meal_item_id}");
        $this->updateMealNutrients($meal_id);
        return true;
    }

    function search($type, $keyword = null, $page = 0, $limit=20){
        $return = array();
        $return['keyword'] = $keyword;
        $return['type'] = $type;
        $return['page'] = $page;

        if( $type == 'recipe' ){
            $yummlyObj = new Yummly();
            $results = $yummlyObj->searchRecipe($keyword,$page,$limit);
            $return['totalCount'] = $results['totalMatchCount'];
            $recipes = array();
            foreach($results['matches'] as $recipe){
                $r = array();
                $r['id'] = $recipe['id'];
                $r['name'] = $recipe['recipeName'];
                $r['time'] = round($recipe['totalTimeInSeconds']/60);
                $r['rating'] = $recipe['rating'];
                $r['image'] = isset($recipe['smallImageUrls'][0]) ? $recipe['smallImageUrls'][0] : null;
                $recipes[] = $r;
            }

            $return['matches'] = $recipes;
            return $return;
        }


        $where = array(
            'deleted=0'
        );
        if( $keyword ){
            $tokens = explode(' ',$keyword);
            foreach($tokens as $t){
                $where[] = " name like '%{$t}%'";
            }
        }
        $where = implode(' AND ',$where);

        $query = "SELECT count(*) cnt FROM shn_foods WHERE {$where}";
        $results = $this->db->select($query);


        $return['totalCount'] = $results[0]['cnt'];
        $start = $page*$limit;
        $query = "SELECT id, name, weights, nutrients FROM shn_foods WHERE {$where} limit {$start},{$limit}";
        $results = $this->db->select($query);
        $foods = array();
        foreach($results as $f){
            $f['nutrients'] = json_decode($f['nutrients'],true);
            $f['weights'] = json_decode($f['weights'],true);
            $weight = current($f['weights']);
            foreach($f['nutrients'] as $k=>$v){
                //print_r($weight);
                $v['value'] = ($v['value']/100)*$weight['weight'];
                $f['weight'] = $weight;
                $f['nutrients'][$k] = $v;
            }
            $foods[] = $f;
        }

        $return['pagination'] = $this->db->paginate($return['totalCount'], $limit, ($page), 'javascript:loadPagination("' . elgg_get_site_url() . 'meal_plans/meal_edit_items_search',"&q=".$_GET['q']."&type=".$_GET['type']."&meal_id=" .$_GET['meal_id'] . '")');
        $return['matches'] = $foods;
        return $return;
    }

    function addMealToMealPlan( $meal_plan_id, $meal_id ){
        $meal_plan = $this->db->select("SELECT * FROM shn_meal_plans WHERE id={$meal_plan_id}");
        if( empty($meal_plan) ){ return false; }

        $meals = array();
        if( !empty($meal_plan[0]['meals']) ){
            $meals = json_decode($meal_plan[0]['meals'],true);
        }

        if( !in_array($meal_id,$meals) ){
            $meals[] = $meal_id;
            $this->db->update('shn_meal_plans',array(
                'meals' => $this->db->mySQLSafe(json_encode($meals))
            ),"id={$meal_plan_id}");
            return true;
        }

        return true;
    }

    function removeMeal($meal_id){
        if ($meal_id!=''){
            $temp_data['deleted'] = 1;
            $meal = $this->db->update("shn_meals", $temp_data, "id=" . $this->db->mySQLSafe($meal_id));
            if( !empty($meal) ){ 
                return true; 
            }
        }
        return false;
    }
	
    function removeMealPlan( $meal_plan_id ){
        if ($meal_plan_id && !empty($meal_plan_id)){

            $plan_id = $this->db->mySQLSafe($meal_plan_id);
            // mp set mp.deleted = 1
            //$meal_plan = $this->db->query("UPDATE shn_meal_plans mp SET mp.deleted = 1 WHERE mp.id={$plan_id}");
            $temp_data['deleted'] = 1;
            $meal_plan = $this->db->update("shn_meal_plans", $temp_data, "id={$plan_id}");
            if( !empty($meal_plan) ){ 
                return true; 
            }
        }
        return false;
    }

    function removeMealFromMealPlan( $meal_plan_id, $meal_id ){
        $meal_plan = $this->db->select("SELECT * FROM shn_meal_plans WHERE id={$meal_plan_id}");
        if( empty($meal_plan) ){ return false; }

        $meals = array();
        if( !empty($meal_plan[0]['meals']) ){
            $meals = json_decode($meal_plan[0]['meals'],true);
        }

        if( ($index = array_search($meal_id,$meals)) !== FALSE ){
            unset($meals[$index]);
            $this->db->update('shn_meal_plans',array(
                'meals' => $this->db->mySQLSafe(json_encode($meals))
            ),"id={$meal_plan_id}");
            return true;
        }

        return true;
    }

// mine
    function getPlanMeals( $meal_plan_id = null ){
        if($meal_plan_id){
            $plan_meals = $this->db->select("SELECT meals, id FROM shn_meal_plans WHERE id={$meal_plan_id}");
            $meals = array();
            if( !empty($plan_meals[0]['meals']) ){
                $meals = json_decode($plan_meals[0]['meals'],true);
            }
            $meals_list = implode(', ', $meals);
            $plan_meals_list = '';
            if ($meals_list != ''){
                $plan_meals_list = $this->db->select("SELECT * FROM shn_meals WHERE id in ({$meals_list})");
                return $plan_meals_list;
            }
        }
        return false;
    }
    function saveBasicMealPlan( $mp_basic){

        if(isset($mp_basic)){
            if(!isset($mp_basic['id'])){
                $data['name'] = $this->db->mySQLSafe($mp_basic['name']);
                $data['attributes'] = $this->db->mySQLSafe($mp_basic['attributes']);

                $this->db->insert('shn_meal_plans',$data);
                $shn_meal_plan_id = $this->db->insertid();
                return $shn_meal_plan_id;
            }else{
                $data['name'] = $this->db->mySQLSafe($mp_basic['name']);
                $data['attributes'] = $this->db->mySQLSafe($mp_basic['attributes']);

                $id = $this->db->mySQLSafe($mp_basic['id']);
                $this->db->update('shn_meal_plans',$data, "id=$id");
                return $id;
            }
        }else{
            return false;
        }
    }    
    function getBasicMealPlan( $plan_id){
        return $this->db->select("SELECT * FROM shn_meal_plans WHERE id={$plan_id}");
    }

    function getRecipe(  $id ){
        if( is_numeric($id) ){
            $result = $this->db->select("SELECT * FROM shn_yummly_recipes WHERE id={$id}");
            if( empty($result) ){ return false; }
            $yummly_id = $result[0]['yummly_id'];
        }else{
            $yummly_id = $id;
        }
        $yummlyObj = new Yummly();
        $recipe = $yummlyObj->getRecipe($yummly_id);

        $data = array();
        $data['id'] = $recipe['shn_recipe_id'];
        $data['name'] = $recipe['name'];
        $data['time'] = round($recipe['totalTimeInSeconds']/60);
        $data['servings'] = $recipe['yield'];
        if( isset($recipe['images'][0]) ){
            $data['image'] = $recipe['images'][0]['hostedLargeUrl'];
        }

        $nutrients = array();
        foreach($recipe['nutritionEstimates'] as $row){
            $nutrients[ $row['attribute'] ] = array(
                'nut_id' => null,
                'value' => $row['value']
            );
        }
		
        $data['ingredients'] = $recipe['ingredientLines'];
        $data['nutrients'] = $nutrients;
        $data['yummly_id'] = $id;
		$data['source'] 	= $recipe['source'];
        return $data;
    }

    function searchMealPlans( $keyword, $page = 1, $limit = 20 ){
        $keyword_search = '';
        if( !empty($keyword) ){
            $keyword_search = "AND name like '%{$keyword}%'";
        }
        $query = "SELECT * FROM shn_meal_plans
        WHERE deleted = 0 {$keyword_search}";
        $results = $this->db->select($query);
        //print_r($results);exit();

        foreach($results as $row_id=>$row){
            $row['nutrients'] = array();
            $meal_ids = implode(',',json_decode($row['meals'],true) );
            if( empty($meal_ids) ){
                $row['meal_count'] = 0;
                $results[$row_id] = $row;
                continue;
            }
            $query = "SELECT * FROM shn_meals WHERE id IN ({$meal_ids}) AND deleted=0";
            $meals = $this->db->select($query);

            if( empty($meals) ){
                $row['meal_count'] = 0;
                $results[$row_id] = $row;
                continue;
            }

            foreach($meals as $meal){
                $nutrients = json_decode($meal['nutrients'],true);
                foreach($nutrients as $k=>$v){
                    if( !isset($row['nutrients'][$k]) ){ $row['nutrients'][$k] = 0; }
                    $row['nutrients'][$k] += $v['value'];
                }
            }
            $row['meal_count'] = count($meals);
            $results[$row_id] = $row;
        }
        return $results;
    }
}