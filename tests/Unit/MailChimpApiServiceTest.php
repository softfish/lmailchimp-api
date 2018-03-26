<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 25/3/18
 * Time: 3:41 PM
 */

namespace Tests\Unit;

use Feikwok\LMailChimp\MailChimpApiService;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailChimpApiServiceTest extends TestCase
{
    private $service;

    public function __construct()
    {
        $this->service = new MailChimpApiService();
    }

    /**
     * Test get available MailChimp's List API
     */
    public function testGetAvailableMailChimpList()
    {
        $response = $this->service->getExistingLists();
        $this->assertTrue($response->success);
    }

    /**
     * Test to create a new MailChimp list API
     *
     * @return void
     */
    public function testCreateMailChimpList()
    {
        $path   = __DIR__ . "/../stubs/api_create_new_list.json";
        $data = json_decode(file_get_contents($path), true);

        $data['name'] = 'testplanlist-'.str_random(5);

        $response = $this->service->createNewList($data);
        // 1. test create list request submitted successful.
        $this->assertTrue($response->success);
        // 2. test the new list is actually existing on MailChimp's account
        $response = $this->service->getExistingLists();
        $availableListNames = [];
        foreach ($response->lists as $list) {
            $availableListNames[] = $list->name;
        }
        $this->assertTrue(in_array($data['name'], $availableListNames));
    }

    /**
     * Test to remove an existing list from MailChimp server
     *
     * @return void
     */
    public function testRemoveExistingMailChimpList()
    {
        $this->cleanup();
    }

    private function cleanup()
    {
        $lists = $this->service->getExistingLists();
        foreach ($lists as $list){
            // If list name is started with 'testplanlist-' then we are going to remove it
            if (preg_match('/testplanlist\-/', $list->name)) {
                $this->service->removeExistingList($list->id);
            }
        }
    }
}
