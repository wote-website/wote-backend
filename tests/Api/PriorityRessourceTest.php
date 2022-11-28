<?php 

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class PriorityRessourceTest extends ApiTestCase
{

	public function testReadPriorityAsAdmin()
	{
		$client = self::createClient();

		// test one public profile
		$client->request('GET', '/api/priorities/12');
		$this->assertResponseStatusCodeSame(200);

		// test one non public profile
		$client->request('GET', '/api/priorities/15');
		$this->assertResponseStatusCodeSame(302);


		$client->request('POST', '/login_api',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => [
				'email' => 'dimitri@gmail.com',
				'password' => 'dimitri'
			]
		]);		
		$this->assertResponseStatusCodeSame(200);


		// test non owned non public profile
		$client->request('GET', '/api/priorities/18');
		$this->assertResponseStatusCodeSame(200);

		// test non owned non public profile
		$client->request('GET', '/api/priorities/15');
		$this->assertResponseStatusCodeSame(200);

	}

	public function testReadPriorityAsUser()
	{
		$client = self::createClient();

		// test one public profile
		$client->request('GET', '/api/priorities/12');
		$this->assertResponseStatusCodeSame(200);

		// test one non public profile
		$client->request('GET', '/api/priorities/15');
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
		$client->request('GET', '/api/priorities/76');
		$this->assertResponseStatusCodeSame(200);

		// test non owned non public profile
		$client->request('GET', '/api/priorities/15');
		$this->assertResponseStatusCodeSame(403);

	}
}