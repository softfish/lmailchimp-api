<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 26/3/18
 * Time: 1:53 AM
 */
namespace Feikwok\LMailChimp\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Feikwok\LMailChimp\MailChimpApiService;
use Illuminate\Http\Request;

class MemberApiController extends Controller
{
    /**
     * Adding new member to the MailChimp's list
     *
     * @param Request $request
     * @param $list_id
     * @return mixed
     */
    public function store(Request $request, $list_id)
    {
        $result = app('MailChimpApiService')->addMemberToList($list_id, $request->all());

        if ($result) {
            return response([
                'success' => true,
                'message' => 'New member has been added to the MailChimp list '.$list_id,
            ]);
        } else {
            return response([
                'success' => false,
                'error' => 'Adding new member to MailChimp list failed. Please check the system log for more info.',
            ]);
        }

    }

    /**
     * Update existing member from specific MailChimp's List
     *
     * @param Request $request
     * @param $list_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $list_id)
    {
        $result = app('MailChimpApiService')->updateMemberOnList($list_id, $request->all());

        if ($result) {
            return response([
                'success' => true,
                'message' => 'The member detail has been updated',
            ]);
        } else {
            return response([
                'success' => false,
                'error' => 'Update member detail error. Please check the system log for more info.'
            ]);
        }
    }

    /**
     * Delete existing member from specific MailChimp's List
     *
     * @param Request $request
     * @param $list_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, $list_id)
    {
        $result = app('MailChimpApiService')->removeMemberFromList($list_id, $request->get('email'));

        if ($result) {
            return response([
                'success' => true,
                'message' => 'Member has been removed from the MailChimp list.'
            ]);
        } else {
            return response([
                'success' => false,
                'error' => 'Remove member from MailChimp list error. Please check the system log for more info.'
            ]);
        }

    }
}