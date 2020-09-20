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
     * /api/get?from=users&where={id=2,username=test}&order_by={field=id,order=asc}&select=id,full_name
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

        return response()->json($data,200);
    }

    /**
     * To update all tables with id param
     * @example
     * curl -X POST \
     *    /api/update \
     *   -H 'cache-control: no-cache' \
     *   -H 'content-type: application/x-www-form-urlencoded' \
     *   -H 'postman-token: 3f516fa9-0abc-cf62-8d2f-839e90d4b4bd' \
     *   -d 'table=users&data=%7Bfull_name%3DAnil%20Pokhariyal%2Cphone%3D9699919998%7D&id=1'
     * @param Request $request
     * @return JsonResponse|void
     */
    public function updateData(Request $request){
        $table = $request->get('table',null);
        $update = $request->get('data','{}');
        $id = $request->get('id',0);
        if(!$id){
            return abort(400,"id param is required");
        }
        if(!$table){
            return abort(400, "table param is required");
        }
        if(!Schema::hasTable($table)){
            return abort(400, "Table does not exits in database");
        }
        $update = $this->decorateArray($update);
        if(count($update)==0){
            return abort(400,"data param is missing to update");
        }else{
            foreach ($update as $field=>$value){
                if(!Schema::hasColumn($table, $field)){
                    return abort(400, "Field ".$field." not exists in table which is used in where param");
                }
            }
        }

        DB::table($table)->where('id',$id)->update($update);
        return response()->json("Table updated succesfully.",200);
    }

    /**
     * @example
     * curl -X DELETE \
     *    /api/delete \
     *   -H 'cache-control: no-cache' \
     *   -H 'content-type: application/x-www-form-urlencoded' \
     *   -H 'postman-token: 68ca247d-ed29-281c-96ff-1ec5804f3e88' \
     *   -d 'id=2&table=users'
     * @param Request $request
     * @return JsonResponse|void
     */
    public function deleteData(Request $request){
        $table = $request->get('table',null);
        $id = $request->get('id',null);
        if(!$id){
            return abort(400,"id param is required");
        }
        if(!$table){
            return abort(400, "table param is required");
        }
        if(!Schema::hasTable($table)){
            return abort(400, "Table does not exits in database");
        }
        DB::table($table)->where('id',$id)->delete();

        return response()->json("Table data deleted succesfully.",200);
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
