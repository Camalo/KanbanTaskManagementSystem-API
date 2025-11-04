<?

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use DateTimeImmutable;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Project;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\Task;
use Kamalo\KanbanTaskManagementSystem\Domain\Entity\User;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Description;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testTaskInitialization(): void
    {
        $owner = $this->mockUser();
        $assignee = $this->mockUser(2);
        $project = $this->mockProject();
        $title = new Title('Test task');
        $description = new Description('Some desc');
        $dueDate = new DateTimeImmutable('+1 day');

        $task = new Task(
            null,
            $title,
            $project,
            $description,
            $owner,
            $assignee,
            $dueDate
        );

        $this->assertNull($task->getId());
        $this->assertSame($title, $task->getTitle());
        $this->assertSame($description, $task->getDescription());
        $this->assertSame($owner, $task->getOwner());
        $this->assertSame($assignee, $task->getAssignee());
        $this->assertSame($project, $task->getProject());
        $this->assertSame($dueDate, $task->getDueDate());
    }

    public function testUpdateFields(): void
    {
        $task = new Task(null, new Title('Old'), $this->mockProject(), null, $this->mockUser(), null, null);

        $newTitle = new Title('New title');
        $newDesc = new Description('New desc');
        $newProject = $this->mockProject();
        $newUser = $this->mockUser(3);
        $newDue = new DateTimeImmutable('+2 days');

        $task->updateTitle($newTitle);
        $task->updateDescription($newDesc);
        $task->updateProject($newProject);
        $task->updateAssignee($newUser);
        $task->updateDueDate($newDue);

        $priority = new TaskPriority(2); // HIGH/MED/LOW etc
        $task->updatePriority($priority);
        $task->setStatus('in_progress');

        $this->assertSame($newTitle, $task->getTitle());
        $this->assertSame($newDesc, $task->getDescription());
        $this->assertSame($newProject, $task->getProject());
        $this->assertSame($newUser, $task->getAssignee());
        $this->assertSame($newDue, $task->getDueDate());
        $this->assertEquals(2, $task->getPriority());
        $this->assertEquals('in_progress', $task->getStatus());
    }

    public function testCompletedAtNotSetWhenStatusNotDone(): void
    {
        $task = new Task(null, new Title('X'), $this->mockProject(), null, $this->mockUser(), null, null);
        $task->prePersist();

        $task->setStatus('in_progress');
        $task->preUpdate();

        $this->assertNull($task->getCompletedAt());
    }

    private function mockUser(int $id = 1): User
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($id);
        return $user;
    }

    private function mockProject(): Project
    {
        return $this->createMock(Project::class);
    }
}
