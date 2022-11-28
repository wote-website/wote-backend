<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SmokeControllerTest extends WebTestCase
{
    /**
     * @dataProvider getPublicUrls
     */
    public function testSomething(string $url)
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $crawler = $client->request('GET', $url);

        
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('body')->count());
    }


    public function getPublicUrls()
    {
        yield ['/login'];
        yield ['/register'];
        yield ['/api'];
        yield ['/'];
    }



    public function testAdminSecureUrlsNotLogged()
    {
        $client = static::createClient();
        $craler = $client->request('GET', '/admin/theme/');

        $this->assertResponseRedirects(
            '/login'
        );

        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('html h1', 'Please sign in');

        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'dimitri@gmail.com';
        $form['password'] = 'dimitri';
        $crawler = $client->submit($form);


        $this->assertResponseRedirects(
            'http://localhost/admin/theme/'
        );

        $crawler = $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('title', 'Theme index');


    }

    
    /**
     * @todo Remove the 2nd followredirect --> done: 2nd redirect to add the final '/' !
     * Solved by integration in the initial route
     */
    public function testAdminSecureUrlsNotLoggedTheme()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://localhost/admin/theme/');


        $this->assertResponseRedirects(
            '/login'
        );

        $crawler = $client->followRedirect();


        $form = $crawler->selectButton('Sign in')->form();
        $form['email'] = 'dimitri@gmail.com';
        $form['password'] = 'dimitri';
        $crawler = $client->submit($form);

        // $this->assertResponseRedirects(
        //     'http://localhost/admin/theme'
        // );
        // echo $client->getResponse()->getContent();
        // $crawler = $client->followRedirect();

        // Why do I need to redirect 2 times to the same page ?
        // echo $client->getResponse()->getContent();

        $this->assertResponseRedirects(
            'http://localhost/admin/theme/'
        );
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Theme index');

        $logFile = fopen(__DIR__.'/../web/log.html', 'w+');


        fwrite($logFile, $client->getResponse()->getContent());
        fclose($logFile);

    }

    /**
     * @dataProvider getAdminSecureUrls
     */
    public function AdminSecureUrlsLogged(string $url)
    {
        $client = static::createClient();
        
        // example taken from demo project
        // $client = static::createClient([], [
        //     'PHP_AUTH_USER' => 'dimitri@gmail.com',
        //     'PHP_AUTH_PW' => 'dimitri',
        // ]);

        // the method "loginUser" is not detected while it is promoted by symfony doc (warning, available since 5.1 version only, maybe update needed)
        // $userRepository = static::$container->get(UserRepository::class);
        // $testUser = $userRepository->findOneByEmail('dimitri@gmail.com');
        // $client->loginUser($testUser);

        $client->request('GET', $url);

        $response = $client->getResponse();

        //$this->assertResponseIsSuccessful();
    }


    public function getAdminSecureUrls()
    {
        yield ['/admin/theme/'];
        yield ['/admin/profile/'];
    }
}
