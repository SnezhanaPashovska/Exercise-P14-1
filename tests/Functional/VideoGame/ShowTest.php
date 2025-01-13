<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ShowTest extends FunctionalTestCase
{
    public function testShouldShowVideoGame(): void
    {
        $this->get('/jeu-video-0');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Jeu vidéo 0');
    }

    public function testShouldPostReview(): void
    {
        // Simulate login
        $this->login();

        // Access the review page
        $crawler = $this->client->request('GET', '/jeu-video-49');
        self::assertResponseIsSuccessful();

        // Find the form by the button text and get the form object
        $form = $crawler->selectButton('Poster')->form();

        // Submit the form with the review data
        $this->client->submit($form, [
            'review[rating]' => 4,
            'review[comment]' => 'Mon commentaire',
        ]);

        // Assert response after submission
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->client->followRedirect(); // Follow the redirect after submission

        // Assert the content in the last list item (review posted)
        self::assertSelectorTextContains('div.list-group-item:last-child h3', 'user+0');
        self::assertSelectorTextContains('div.list-group-item:last-child p', 'Mon commentaire');
        self::assertSelectorTextContains('div.list-group-item:last-child span.value', '4');
    }
}
