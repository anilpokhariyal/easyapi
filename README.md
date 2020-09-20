<h1>EasyAPI for beginner in lumen</h1>
<p>
easy use api for get post update and delete on whole db with few predefined apis
</p>

<h2>GET</h2>
<pre>
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
 </pre>
 
 <h2>CREATE</h2>
 <pre>
 /**
  * Create entry in table
  * @example
  *   curl -X POST \
  *    /api/create \
  *   -H 'cache-control: no-cache' \
  *   -H 'content-type: application/x-www-form-urlencoded' \
  *   -H 'postman-token: f5cd31d5-ce84-5326-ff4d-3a2f9664d5b7' \
  *   -d 'table=users&data=%7Busername%3Dtest%2Cfull_name%3DAnil%20Pokhariyal%2Cphone%3D9675517098%2Cemail%3Dtest%40gmail.com%2Ccity%3Dtest%20city%7D'
  * @param Request $request
  * @return JsonResponse|void
  */
 </pre>
 
 <h2>UPDATE</h2>
 <pre>
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
 </pre>

<h2>DELETE</h2>
<pre>
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
</pre>
