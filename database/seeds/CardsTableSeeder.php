<?php

use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use App\Models\Card;

class CardsTableSeeder extends Seeder
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
                Card::create($card);

            }
        }
        return $this->apiResponse();
    }

    public function apiResponse()
    {
        return response()->json(['msg' => 'Seed of cards completed']);
    }
}