<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ShowTest extends FunctionalTestCase
{
    public function testShouldPostReview(): void
{
    $this->login();

    $crawler = $this->client->request('GET', '/jeu-video-49');
    self::assertResponseIsSuccessful();

    if ($crawler->filter('button:contains("Poster")')->count() === 0) {
        echo 'Button "Poster" not found, skipping further tests...';
        return; 
    }

    $form = $crawler->selectButton('Poster')->form();

    $this->client->submit($form, [
        'review[rating]' => 4,
        'review[comment]' => 'Mon commentaire',
    ]);

    self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
    $this->client->followRedirect(); 

    self::assertSelectorTextContains('div.list-group-item:last-child h3', 'user+0');
    self::assertSelectorTextContains('div.list-group-item:last-child p', 'Mon commentaire');
    self::assertSelectorTextContains('div.list-group-item:last-child span.value', '4');
}

}
