<?

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Description;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase{
    
    public function testProjectInitialization(): void
    {
        $owner = $this->mockUser();
        $title = new Title('Test Project');
        $description = new Description('Description');

        $project = new Project(null, $title, $description, $owner);

        $this->assertNull($project->getId());
        $this->assertSame($title, $project->getTitle());
        $this->assertSame($description, $project->getDescription());
        $this->assertSame($owner, $project->getOwner());
        $this->assertCount(0, $project->getMembers());
    }

    public function testAddMember(): void
    {
        $owner = $this->mockUser();
        $member = $this->mockUser(2);

        $project = new Project(null, new Title('Test'), null, $owner);
        $project->addMember($member);

        $this->assertTrue($project->hasMember($member));
        $this->assertCount(1, $project->getMembers());
    }

    public function testRemoveMember(): void
    {
        $owner = $this->mockUser();
        $member = $this->mockUser(2);

        $project = new Project(null, new Title('Test'), null, $owner);
        $project->addMember($member);
        $project->removeMember($member);

        $this->assertFalse($project->hasMember($member));
        $this->assertCount(0, $project->getMembers());
    }

    public function testUpdateTitleAndDescription(): void
    {
        $owner = $this->mockUser();
        $project = new Project(null, new Title('Old'), null, $owner);
        
        $newTitle = new Title('New Title');
        $newDesc = new Description('New Desc');

        $project->updateTitle($newTitle);
        $project->updateDescription($newDesc);

        $this->assertSame($newTitle, $project->getTitle());
        $this->assertSame($newDesc, $project->getDescription());
    }

    private function mockUser(int $id = 1): User
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($id);

        return $user;
    }
}