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

class MailChimpApiService {

    private $client;

    /**
     * MailChimpApiService constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!env('LMAILCHIMP_API_KEY') && (preg_match('/\-/', env('LMAILCHIMP_API_KEY')))) {
            throw new \Exception('MailChimp API required');
        }
        $apiKey = env('LMAILCHIMP_API_KEY');

        [$key, $dc] = explode('-', $apiKey);

        $this->client = new Client([
            'base_uri' => "https://$dc.api.mailchimp.com/3.0/",
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
        } catch (ClientException $e) {
            dd($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $this->getResponse($response);
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
        } catch (ClientException $e) {
            dd($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $this->getResponse($response);
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
            dd($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            dd($e->getMessage());
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
            dd($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            dd($e->getMessage());
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
        } catch (ClientException $e) {
            dd($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return $this->getResponse($response);
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
            dd($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            dd($e->getMessage());
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

