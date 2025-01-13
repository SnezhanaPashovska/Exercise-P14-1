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

        $this->client->submitForm('S\'inscrire', self::getFormData());

        self::assertResponseRedirects('/auth/login');

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
     * @return iterable<string, array<string, string>> Invalid form data with descriptions.
     */
    public static function provideInvalidFormData(): iterable
    {
        yield 'empty username' => self::getFormData(['register[username]' => '']);
        yield 'non unique username' => self::getFormData(['register[username]' => 'user+1']);
        yield 'too long username' => self::getFormData(['register[username]' => 'Lorem ipsum dolor sit amet orci aliquam']);
        yield 'empty email' => self::getFormData(['register[email]' => '']);
        yield 'non unique email' => self::getFormData(['register[email]' => 'user+1@email.com']);
        yield 'invalid email' => self::getFormData(['register[email]' => 'fail']);
    }

    /**
     *  Returns the form data with optional overrides.
     * 
     * @param array<string, string> $overrideData Data to override the default form data
     * @return array<string, string> The form data
     */
    public static function getFormData(array $overrideData = []): array
    {
        return [
            'register[username]' => 'username',
            'register[email]' => 'user@email.com',
            'register[plainPassword]' => 'SuperPassword123!',
        ] + $overrideData;
    }
}
