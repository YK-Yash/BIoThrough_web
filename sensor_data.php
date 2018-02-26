<?php

namespace Firebase;
require_once "firebaseLib.php";
use Exception;
class FirebaseTest
{
    protected $_firebase;
    protected $_todoMilk = array(
        'name' => 'Pick the milk',
        'priority' => 1
    );

    protected $_todoBeer = array(
        'name' => 'Pick the beer',
        'priority' => 2
    );

    protected $_todoLEGO = array(
        'name' => 'Pick the LEGO',
        'priority' => 3
    );

    // --- set up your own database here
    const DEFAULT_URL = '';
    const DEFAULT_TOKEN = '';
    const DEFAULT_TODO_PATH = '/Temperature';
    const DELETE_PATH = '/sample';
    const DEFAULT_SET_RESPONSE = '{"name":"Pick the milk","priority":1}';
    const DEFAULT_UPDATE_RESPONSE = '{"name":"Pick the beer","priority":2}';
    const DEFAULT_PUSH_RESPONSE = '{"name":"Pick the LEGO","priority":3}';
    const DEFAULT_DELETE_RESPONSE = 'null';
    const DEFAULT_URI_ERROR = 'You must provide a baseURI variable.';

    public function setUp()
    {
        $this->_firebase = new FirebaseLib(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testNoBaseURI()
    {
        $errorMessage = null;
        try {
            new FirebaseLib();
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        $this->assertEquals(self::DEFAULT_URI_ERROR, $errorMessage);
    }

#    public function testSet($dataaa)
#    {
#        $response = $this->_firebase->set(self::DEFAULT_TODO_PATH, $dataaa);
#        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
#    }
    
    public function testSet($dataaa,$pathh)
    {
        $response = $this->_firebase->set($pathh, $dataaa);
        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testGetAfterSet($pathhh)
    {
        $response = $this->_firebase->get($pathhh);
        echo "Value recieved from firebase.. ".$response;
        return $response;
        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testUpdate()
    {
        $response = $this->_firebase->update(self::DEFAULT_TODO_PATH, $this->_todoBeer);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testGetAfterUpdate()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testPush()
    {
        $response = $this->_firebase->push(self::DEFAULT_TODO_PATH, $this->_todoLEGO);
        $this->assertRegExp('/{"name"\s?:\s?".*?}/', $response);
        return $this->_parsePushResponse($response);
    }

    /**
     * @depends testPush
     */
    public function testGetAfterPush($responseName)
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH . '/' . $responseName);
        $this->assertEquals(self::DEFAULT_PUSH_RESPONSE, $response);
    }

    public function testDelete()
    {
        $response = $this->_firebase->delete(self::DELETE_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    public function testGetAfterDELETE()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    /**
     * @param $response
     * @return mixed
     */
    private function _parsePushResponse($response)
    {
        $responseObj = json_decode($response);
        return $responseObj->name;
    }
}

$fb = new FirebaseTest();
$fb->setUp();

$flag_irrigate = $fb->testGetAfterSet("/Irrigate");
$flag_irrigate_auto = $fb->testGetAfterSet("/AutoIrrigate");

echo $flag_irrigate;
?>
