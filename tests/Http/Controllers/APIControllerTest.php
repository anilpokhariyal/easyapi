<?php

namespace Http\Controllers;

use App\Http\Controllers\APIController;

class APIControllerTest extends \TestCase
{

    public function testGetData()
    {
        $result = $this->json('GET','/api/get?from=users&where={id=2,username=test}&order_by={field=id,order=asc}&select=id,full_name');
        $result->assertResponseStatus(200);
    }

    public function testUpdateData(){
        $result = $this->json('POST','/api/update',['table'=>'users','id'=>1,'data'=>'{username=testing,phone=123456789}']);
        $result->assertResponseStatus(200);
    }

    public function testDeleteData(){
        $result = $this->json('DELETE','/api/delete',['table'=>'users','id'=>1]);
        $result->assertResponseStatus(200);
    }

    public function testDecorateArray()
    {
        $response = (new APIController())->decorateArray("{id=2,username=test}");
        $this->assertTrue($response['id']==2);
        $this->assertTrue($response['username']=="test");
    }
}
