<?php 

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class ProfileRessourceTest extends ApiTestCase
{
	public function testReadProfileAsAdmin()
	{
		$client = self::createClient();

		// test one public profile
		$client->request('GET', '/api/profiles/7');
		$this->assertResponseStatusCodeSame(200);

		// test one non public profile
		$client->request('GET', '/api/profiles/8');
		$this->assertResponseStatusCodeSame(302);


		$client->request('POST', '/login_api',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => [
				'email' => 'dimitri@gmail.com',
				'password' => 'dimitri'
			]
		]);		
		$this->assertResponseStatusCodeSame(200);


		// test one non public profile
		$client->request('GET', '/api/profiles/9');
		$this->assertResponseStatusCodeSame(200);


		// $client->request('POST',
		// 	'/api/profiles',
		// 	['headers' => ['Content-Type' => 'application/json'],
		// 	'json' => ['title' => 'profil de test']]
		// );
		// $this->assertResponseStatusCodeSame(200);

	}

	public function testReadProfileAsUser()
	{
		$client = self::createClient();

		// test one public profile
		$client->request('GET', '/api/profiles/7');
		$this->assertResponseStatusCodeSame(200);

		// test one non public profile
		$client->request('GET', '/api/profiles/8');
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
		$client->request('GET', '/api/profiles/29');
		$this->assertResponseStatusCodeSame(200);

		// test non owned non public profile
		$client->request('GET', '/api/profiles/8');
		$this->assertResponseStatusCodeSame(403);

	}
}