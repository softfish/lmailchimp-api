<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 25/3/18
 * Time: 12:45 AM
 */
namespace Feikwok\LMailChimp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class MailChimpApiService {

    private $client;

    /**
     * MailChimpApiService constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!config('lmailchimp.mailchimp.api_key') && (preg_match('/\-/', config('lmailchimp.mailchimp.api_key')))) {
            throw new \Exception('MailChimp API required');
        }
        $apiKey = config('lmailchimp.mailchimp.api_key');

        [$key, $dc] = explode('-', $apiKey);

        $this->client = new Client([
            'base_uri' => "https://$dc.".config('lmailchimp.mailchimp.api_base_uri'),
            'auth'     => ['apikey', $key],
        ]);
    }

    /**
     * Get existing available list from MailChimp account
     *
     * @return mixed
     */
    public function getExistingLists()
    {
        $response = $this->client->get('lists');
        return $this->getResponse($response);
    }

    /**
     * Create new List from MailChimp's account
     *
     * @param $postData
     * @return mixed
     */
    public function createNewList($postData)
    {
        try {
            $postData = $this->reformMailChimpRequestData($postData);
            $response = $this->client->request('POST', 'lists', [
                'json' => $postData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            return $this->getResponse($response);

        } catch (ClientException $e) {
            Log::error("Create New MailChimp List Error: ".$e->getResponse()->getBody()->getContents());
            return null;
        } catch (\Exception $e) {
            Log::error("Create New MailChimp List Error: ".$e->getMessage());
            return null;
        }
    }

    /**
     * Update existing list from MailChimp's account
     *
     * @param $postData
     * @return mixed
     */
    public function updateExistingList($listId, $postData)
    {
        try {
            $postData = $this->reformMailChimpRequestData($postData);
            $response = $this->client->request('PATCH', 'lists/'.$listId, [
                'json' => $postData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            return $this->getResponse($response);

        } catch (ClientException $e) {
            Log::error("Update Existing MailChimp List Error: ".$e->getResponse()->getBody()->getContents());
            return null;
        } catch (\Exception $e) {
            Log::error("Update Existing MailChimp List Error: ".$e->getMessage());
            return null;
        }
    }

    /**
     * Remove existing list from MailChimp's account
     *
     * @param $listId
     */
    public function removeExistingList($listId)
    {
        try {
            $response = $this->client->delete('lists/'.$listId);
            if ($response->getStatusCode() === 204) {
                return true;
            } else {
                return false;
            }
        } catch (ClientException $e) {
            Log::error("Remove Existing MailChimp List Error: ".$e->getResponse()->getBody()->getContents());
            return false;
        } catch (\Exception $e) {
            Log::error("Remove Existing MailChimp List Error: ".$e->getMessage());
            return false;
        }
    }

    /**
     * Adding Member to existing MailChimp's list
     *
     * @param $listId
     * @param $postData
     * @return mixed
     */
    public function addMemberToList($listId, $postData)
    {
        try {
            $postData = $this->reformMailChimpRequestData($postData);
            $response = $this->client->request('POST', 'lists/'.$listId.'/members', [
                'json' => $postData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (ClientException $e) {
            Log::error("Add New MailChimp Member Error: ".$e->getResponse()->getBody()->getContents());
            return null;
        } catch (\Exception $e) {
            Log::error("Add New MailChimp Member Error: ".$e->getMessage());
            return null;
        }
        return $this->getResponse($response);
    }

    /**
     * Update member to existing MailChimp's list
     *
     * @param $listId
     * @param $postData
     * @return mixed
     */
    public function updateMemberOnList($listId, $postData)
    {
        try {
            $postData = $this->reformMailChimpRequestData($postData);
            $response = $this->client->request('PATCH', 'lists/'.$listId.'/members/'.md5($postData['email_address']), [
                'json' => $postData,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            return $this->getResponse($response);

        } catch (ClientException $e) {
            Log::error("Update Existing MailChimp Member Error: ".$e->getResponse()->getBody()->getContents());
            return null;
        } catch (\Exception $e) {
            Log::error("Update Existing MailChimp Member Error: ".$e->getMessage());
            return null;
        }
    }

    /**
     * Remove Memmber from MailChimp's List
     *
     * @param $listId
     * @param $email
     * @return bool
     */
    public function removeMemberFromList($listId, $email)
    {
        try {
            $response = $this->client->delete('lists/'.$listId.'/members/'.md5($email));
            if ($response->getStatusCode() === 204) {
                return true;
            } else {
                return false;
            }
        } catch (ClientException $e) {
            Log::error("Remove Existing MailChimp Member Error: ".$e->getResponse()->getBody()->getContents());
            return null;
        } catch (\Exception $e) {
            Log::error("Remove Existing MailChimp Member Error: ".$e->getMessage());
            return null;
        }
    }

    /**
     * Processing response contents
     *
     * @param $response
     * @return mixed
     */
    private function getResponse($response)
    {
       return json_decode($response->getBody()->getContents());
    }

    /**
     * Make sure we have the right structure for MailChimp request
     *
     * @param $postData
     * @return mixed
     */
    private function reformMailChimpRequestData($postData)
    {
        foreach ($postData as $key => $value) {
            if (preg_match('/\./', $key)) {
                [$group, $subKey] = explode('.', $key);
                if (in_array($subKey, ['address2']) && $value === null) {
                    $postData[$group][$subKey] = '';
                } else {
                    $postData[$group][$subKey] = $postData[$key];
                }
                unset($postData[$key]);
            }
            if (in_array($key, ['notify_on_subscribe', 'notify_on_unsubscribe']) && $value === null) {
                $postData[$key] = '';
            }
        }
        return $postData;
    }
}

