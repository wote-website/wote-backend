<?php 

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class WeightingRessourceTest extends ApiTestCase
{

	public function testReadWeightingAsAdmin()
	{
		$client = self::createClient();

		// test one public profile
		$client->request('GET', '/api/weightings/30');
		$this->assertResponseStatusCodeSame(200);

		// test one non public profile
		$client->request('GET', '/api/weightings/56');
		$this->assertResponseStatusCodeSame(302);


		$client->request('POST', '/login_api',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => [
				'email' => 'dimitri@gmail.com',
				'password' => 'dimitri'
			]
		]);		
		$this->assertResponseStatusCodeSame(200);


		// test owned non public profile
		$client->request('GET', '/api/weightings/55');
		$this->assertResponseStatusCodeSame(200);

		// test non owned non public profile
		$client->request('GET', '/api/weightings/56');
		$this->assertResponseStatusCodeSame(200);

	}

	public function testReadWeightingAsUser()
	{
		$client = self::createClient();

		// test one public profile
		$client->request('GET', '/api/weightings/30');
		$this->assertResponseStatusCodeSame(200);

		// test one non public profile
		$client->request('GET', '/api/weightings/56');
		$this->assertResponseStatusCodeSame(302);


		$client->request('POST', '/login_api',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => [
				'email' => 'devwcs@gmail.com',
				'password' => 'devwcs'
			]
		]);		
		$this->assertResponseStatusCodeSame(200);


		// test owned non public profile
		$client->request('GET', '/api/weightings/535');
		$this->assertResponseStatusCodeSame(200);

		// test non owned non public profile
		$client->request('GET', '/api/weightings/55');
		$this->assertResponseStatusCodeSame(403);

	}

	public function testWriteWeightingAsUser()
	{
		$client = self::createClient();

		$body = [
			'profile' => '/api/profiles/7',
			'criterion' => '/api/criteria/5',
			'comment' => 'no comment',
			'value' => 10
		];

		$client->request('POST', '/api/weightings',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => $body
		]);	
		// Need to be authentified to access POST entrypoint	
		$this->assertResponseStatusCodeSame(302);


		$client->request('POST', '/login_api',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => [
				'email' => 'devwcs@gmail.com',
				'password' => 'devwcs'
			]
		]);		
		$this->assertResponseStatusCodeSame(200);


		$client->request('POST', '/api/weightings',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => $body
		]);	
		// not owned profile forbidden even if isPublic
		$this->assertResponseStatusCodeSame(403);

		// set owned profile
		$body['profile'] = '/api/profiles/29';

		$client->request('POST', '/api/weightings',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => $body
		]);	
		// creation no possible on same criterion as existing weighting
		$this->assertResponseStatusCodeSame(409);
	}
}