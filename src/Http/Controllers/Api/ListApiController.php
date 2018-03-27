<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 25/3/18
 * Time: 12:08 AM
 */
namespace Feikwok\LMailChimp\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Feikwok\LMailChimp\Http\Requests\LMailChimpCreateListRequest;
use Feikwok\LMailChimp\MailChimpApiService;
use Illuminate\Http\Request;

class ListApiController extends Controller
{
    /**
     * Loading existing lists from MailChimp's account
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $result = app('MailChimpApiService')->getExistingLists();

        if ($result) {
            return response([
                'success' => true,
                'lists' => $result->lists,
                'total_items' => $result->total_items,
            ]);
        } else {
            return response([
                'success' => false,
                'error' => 'Load available MailChimp list error.'
            ]);
        }
    }

    /**
     * Create a new MailChimp List
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $result = app('MailChimpApiService')->createNewList($request->all());

        if ($result) {
            return response([
                'success' => true,
                'message' => 'New list has been created.',
                'new_list' => $result,
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Create new MailChimp list error, please see system log for more info.'
            ]);
        }


    }

    /**
     * Update existing MailChimp List
     *
     * @param LMailChimpUpdateListRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $list_id)
    {
        $result = app('MailChimpApiService')->updateExistingList($list_id, $request->all());

        if (!empty($result) && $result != false) {
            return response([
                'success' => true,
                'message' => 'List '.$list_id.' has been updated.',
            ]);
        } else {
            return response([
                'success' => false,
                'error' => 'Update existing MailChimp list error, please see system log for more info.'
            ]);
        }
    }

    /**
     * Remove MailChimp list
     *
     * @param $list_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($list_id)
    {
        $result = app('MailChimpApiService')->removeExistingList($list_id);

        if ($result) {
            return response([
                'success' => true,
                'message' => 'List '.$list_id.' has been removed.',
            ]);
        } else {
            return response([
                'success' => false,
                'error' => 'Request failed! Unable to delete the list.',
            ]);
        }
    }
}