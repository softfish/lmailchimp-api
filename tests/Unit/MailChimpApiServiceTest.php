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
     * Test create a new MailChimp list API
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
     * Test update an existing MailChimp list API
     *
     * @return void
     */
    public function testUpdateMailChimpList()
    {
        $response = $this->service->getExistingLists();
        $testList = null;
        foreach ($response->lists as $list) {
            if (preg_match('/testplanlist\-/', $list->name)) {
                $testList = $list;
            }
        }

        $path   = __DIR__ . "/../stubs/api_create_new_list.json";
        $data = json_decode(file_get_contents($path), true);

        $editedTestListName = $testList['name'].' (Edit)';
        $data['name'] = $editedTestListName;

        $listId = $data['id'];

        // reload the existing lists
        $response = $this->service->getExistingLists();

        $listUpdated = false;

        foreach ($response->lists as $list) {
            if ($list->id === $listId) {
                if ($list['name'] === $editedTestListName) {
                    // only when we find the right list and make sure the name has been changed
                    $listUpdated = true;
                }
            }
        }

        $this->assertTrue($listUpdated);
    }

    /**
     * Search an existing member test
     */
    public function testSearchExistingMailChimpMember()
    {
        $response = $this->service->getExistingLists();
        $testAccount = $response->lists[0];

        $response = $this->service->searchMembersFromList($testAccount['id'], $testAccount['email']);

        $this->assertNotEmpty($response->members);
    }

    /**
     * Test to remove an existing list from MailChimp server
     *
     * @return void
     */
    public function testRemoveExistingMailChimpList()
    {
        $this->cleanup();

        // After we clean up / remove the test list from MailChimp account
        $lists = $this->service->getExistingLists();
        $stillHaveTestList = false;
        foreach ($lists as $list){
            // If list name is started with 'testplanlist-' then we are going to remove it
            if (preg_match('/testplanlist\-/', $list->name)) {
                $stillHaveTestList = true;
                // if we have even one test list then the test is failed.
                break;
            }
        }
        $this->assertTrue(!$stillHaveTestList);
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
