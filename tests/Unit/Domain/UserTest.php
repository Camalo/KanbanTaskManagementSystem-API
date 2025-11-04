<?

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\FullName;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testInitialization(): void
    {
        $name = new FullName('John', 'Doe');
        $user = new User(null, 'test@example.com', $name, ['ROLE_USER'], true);

        $this->assertNull($user->getId());
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertSame($name, $user->getName());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertTrue($user->getIsActive());
        $this->assertCount(0, $user->getProjects());
    }

    public function testDefaultRoleApplied(): void
    {
        $name = new FullName('John', 'Doe');
        $user = new User(null, 'a@a.com', $name, [], true);

        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testRejectsInvalidRole(): void
    {
        $name = new FullName('John', 'Doe');

        $this->expectException(\InvalidArgumentException::class);
        new User(null, 'a@a.com', $name, ['ROLE_SUPER_ADMIN'], true);
    }

    public function testEmailCanBeUpdated(): void
    {
        $name = new FullName('John', 'Doe');
        $user = new User(null, 'old@example.com', $name, ['ROLE_USER'], true);

        // valid email
        // skip DNS validation: mock only format
        $email = 'new@example.com';

        $user->updateEmail($email);
        $this->assertEquals('new@example.com', $user->getEmail());
    }

    public function testInvalidEmailFormatThrows(): void
    {
        $name = new FullName('John', 'Doe');
        $user = new User(null, 'test@example.com', $name, ['ROLE_USER'], true);

        $this->expectException(\InvalidArgumentException::class);
        $user->updateEmail('not-an-email');
    }

    public function testPasswordMustBeBcryptHash(): void
    {
        $name = new FullName('John', 'Doe');
        $user = new User(null, 'test@example.com', $name, ['ROLE_USER'], true);

        $user->updatePassword('$2y$somethingvalidhashxxxxxxxxxxxxxx');
        $this->assertEquals('$2y$somethingvalidhashxxxxxxxxxxxxxx', $user->getPassword());
    }

    public function testInvalidPasswordHashThrows(): void
    {
        $name = new FullName('John', 'Doe');
        $user = new User(null, 'test@example.com', $name, ['ROLE_USER'], true);

        $this->expectException(\InvalidArgumentException::class);
        $user->updatePassword('plain-password');
    }

    public function testUpdateTimezone(): void
    {
        $user = new User(null, 'a@a.com', new FullName('A', 'B'), [], true);
        $user->updateTimezone('Europe/Berlin');

        $this->assertEquals('Europe/Berlin', $user->getTimezone());
    }

    public function testUpdateIsActive(): void
    {
        $user = new User(null, 'a@a.com', new FullName('A', 'B'), [], false);

        $user->updateIsActive(true);
        $this->assertTrue($user->getIsActive());
    }

    public function testAddProject(): void
    {
        $user = new User(null, 'a@a.com', new FullName('A', 'B'), [], true);
        $project = $this->mockProject();

        $user->addProject($project);

        $this->assertCount(1, $user->getProjects());
    }

    public function testRemoveProject(): void
    {
        $user = new User(null, 'a@a.com', new FullName('A', 'B'), [], true);
        $project = $this->mockProject();

        $user->addProject($project);
        $user->removeProject($project);

        $this->assertCount(0, $user->getProjects());
    }

    private function mockProject(): Project
    {
        return $this->createMock(Project::class);
    }
}
