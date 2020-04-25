<?php

use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use App\Models\Card;

class CardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $responseJson = $this->makeRequest();
        return $responseJson;

    }

    public function makeRequest()
    {
        $client = new Client();
        $response = $client->request('GET', ENV('API_URL'), [
            'headers' => [
                'x-rapidapi-host' => 'omgvamp-hearthstone-v1.p.rapidapi.com',
                'x-rapidapi-key' => '73321f181fmshdf223145bebde06p13f166jsn78e0894e0541'
            ]
        ]);
        $responseJson = json_decode($response->getBody(), true);
        foreach($responseJson as $card_set)
        {
            foreach($card_set as $card){
                Card::create($this->formatResponse($card));
            }
        }
        return $this->apiResponse();
    }

    public function apiResponse()
    {
        return response()->json(['msg' => 'Seed of cards completed']);
    }

    public function formatResponse($response){
        $card_attrs['name'] = $response['name'];
        $card_attrs['health'] = array_key_exists('health', $response) ? $response['health'] : null;
        $card_attrs['attack'] = array_key_exists('attack', $response) ? $response['attack'] : null;
        $card_attrs['faction'] = array_key_exists('faction', $response) ? $response['faction'] : null;
        $card_attrs['race'] = array_key_exists('race', $response) ? $response['race'] : null;
        $card_attrs['type'] = array_key_exists('type', $response) ? $response['type'] : null;
        $card_attrs['text'] = array_key_exists('text', $response) ? $response['text'] : null;

        return $card_attrs;
    }
}