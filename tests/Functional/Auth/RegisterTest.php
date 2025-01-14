<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Model\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterTest extends FunctionalTestCase
{
    public function testThatRegistrationShouldSucceeded(): void
    {
        $this->get('/auth/register');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('S\'inscrire', self::getFormData());
        //self::assertResponseRedirects('/auth/login');


        $user = $this->getEntityManager()->getRepository(User::class)->findOneByEmail('user@email.com');
        $userPasswordHasher = $this->service(UserPasswordHasherInterface::class);

        self::assertNotNull($user);
        self::assertSame('username', $user->getUsername());
        self::assertSame('user@email.com', $user->getEmail());
        self::assertTrue($userPasswordHasher->isPasswordValid($user, 'SuperPassword123!'));
    }

    /**
     * @dataProvider provideInvalidFormData
     * @param array<string, string> $formData
     */
    public function testThatRegistrationShouldFailed(array $formData): void
    {
        $this->get('/auth/register');

        $this->client->submitForm('S\'inscrire', $formData);

        self::assertResponseIsUnprocessable();
    }

    /**
     * Provides invalid form data with descriptions.
     *
     * @return iterable<string, array{0: array<string, string>}>
     */
    public static function provideInvalidFormData(): iterable
    {
        yield 'empty username' => [['register[username]' => '', 'register[email]' => 'user@email.com', 'register[plainPassword]' => 'SuperPassword123!']];
        yield 'non unique username' => [['register[username]' => 'user+1', 'register[email]' => 'user@email.com', 'register[plainPassword]' => 'SuperPassword123!']];
        yield 'too long username' => [['register[username]' => str_repeat('a', 256), 'register[email]' => 'user@email.com', 'register[plainPassword]' => 'SuperPassword123!']];
        yield 'empty email' => [['register[username]' => 'username', 'register[email]' => '', 'register[plainPassword]' => 'SuperPassword123!']];
        yield 'non unique email' => [['register[username]' => 'username', 'register[email]' => 'user+1@email.com', 'register[plainPassword]' => 'SuperPassword123!']];
        yield 'invalid email' => [['register[username]' => 'username', 'register[email]' => 'fail', 'register[plainPassword]' => 'SuperPassword123!']];
    }

    /**
     * @param array<string, string> $overrideData
     * @return array<string, string>
     */
    public static function getFormData(array $overrideData = []): array
    {
        return [
            'register[username]' => 'ValidUsername',
            'register[email]' => 'validemail@example.com',
            'register[plainPassword]' => 'ValidPassword123!',
        ] + $overrideData;
    }
}
