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
        $result = (new MailChimpApiService())->addMemberToList($list_id, $request->all());

        return response([
            'success' => true,
            'message' => 'New member has been added to the MailChimp list '.$list_id,
        ]);
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
        $result = (new MailChimpApiService())->updateMemberOnList($list_id, $request->all());

        return response([
            'success' => true,
            'message' => 'The member detail has been updated',
        ]);
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
        $result = (new MailChimpApiService())->removeMemberFromList($list_id, $request->get('email'));

        return response([
            'success' => true,
            'message' => 'Member has been removed from the MailChimp list.'
        ]);
    }
}