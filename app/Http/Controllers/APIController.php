<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class APIController extends Controller
{
    /**
     * Common api for all get apis on database tables
     * select = *
     * from = table_name
     * where = {field=value,field=value}
     * limit = 100
     * order_by = {field=field_name,order=asc}
     * group_by = field
     * @example 
     * http://localhost:8000/api/get?from=users&where={id=2,username=test}&order_by={field=id,order=asc}&select=id,full_name
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getData(Request $request){
        $select = $request->get('select', null);
        $from = $request->get('from',null);
        $where = $request->get('where',0);
        $limit = $request->get('limit',100);
        $order_by = $request->get('order_by',null);
        $group_by = $request->get('group_by','id');

        if(!$select){
           $select = '*';
        }else{
            $select = explode(",", $select);
            foreach ($select as $field){
                if(!Schema::hasColumn($from, $field)){
                    return abort(400, "Field ".$field." not exists in table which is used in select param");
                }
            }
        }

        if(!$from){
            return abort(400, "from is required.");
        }

        if(!Schema::hasTable($from)){
            return abort(400, "Table does not exits in database");
        }

        if(!$where){
            $where = 0;
        }else{
            $where = $this->decorateArray($where);
            foreach ($where as $field=>$value){
                if(!Schema::hasColumn($from, $field)){
                    return abort(400, "Field ".$field." not exists in table which is used in where param");
                }
            }
        }
        if(!$order_by){
            $order_by['field'] = 'id';
            $order_by['order'] = 'ASC';
        }else{

            $order_by = $this->decorateArray($order_by);
            if(!isset($order_by['field'])){
                return abort(400, "Order by in wrong format");
            }
            if(!isset($order_by['order'])){
                return abort(400, "Order by in wrong format");
            }
            if(!Schema::hasColumn($from, $order_by['field'])){
                return abort(400, "Order by column not found in table");
            }
            if(!in_array($order_by['order'],['asc','desc','ASC','DESC'])){
                return abort(400, "Order by column can be sort by only asc or desc");
            }
        }

        $data = DB::table($from)->select($select);
        if($where) {
            $data->where($where);
        }
        $data = $data->orderBy($order_by['field'], $order_by['order'])->groupBy($group_by)->limit($limit)->get();

        return response()->json($data);
    }

    /**
     * for creating array from request params
     * @param $param
     * @return array
     */
    public function decorateArray($param){
        $paramArr = explode(",", str_replace('}','',str_replace('{','',$param)));
        $output = [];
        foreach ($paramArr as $arr){
            $tempArr = explode("=", $arr);
            if(count($tempArr)) {
                $output[$tempArr[0]] = $tempArr[1];
            }
        }
        return $output;
    }
}
