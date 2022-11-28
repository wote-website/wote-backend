<?php 

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class UserRessourceTest extends ApiTestCase
{
	public function testReadUsersAsAdmin()
	{
		$client = self::createClient();

		$client->request('GET', '/api/users/1');
		$this->assertResponseStatusCodeSame(302);


		$client->request('POST', '/login_api',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => [
				'email' => 'dimitri@gmail.com',
				'password' => 'dimitri'
			]
		]);		
		$this->assertResponseStatusCodeSame(200);


		$client->request('GET', '/api/users/1');
		$this->assertResponseStatusCodeSame(200);

		$client->request('GET', '/api/users/2');
		$this->assertResponseStatusCodeSame(200);

	}

	public function testReadUsersAsUser()
	{
		$client = self::createClient();

		$client->request('GET', '/api/users/1');
		$this->assertResponseStatusCodeSame(302);


		$client->request('POST', '/login_api',[
			'headers' => ['Content-Type' => 'application/json'],
			'json' => [
				'email' => 'devwcs@gmail.com',
				'password' => 'devwcs'
			]
		]);		
		$this->assertResponseStatusCodeSame(200);

		$client->request('GET', '/api/users/10');
		$this->assertResponseStatusCodeSame(200);

		$client->request('GET', '/api/users/2');
		$this->assertResponseStatusCodeSame(403);

	}
}