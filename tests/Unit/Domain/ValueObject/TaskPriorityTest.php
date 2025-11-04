<?

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\TaskPriority;
use PHPUnit\Framework\TestCase;

class TaskPriorityTest extends TestCase
{

    public function testTaskPriorityFromString(): void
    {
        $priority = new TaskPriority('low');

        $this->assertSame(
            expected: 'low',
            actual: $priority->getKey()
        );

        $this->assertSame(
            expected: 1,
            actual: $priority->getValue()
        );

        $this->assertSame(
            expected: 'Низкий',
            actual: $priority->getLabel()
        );
    }

    public function testTaskPriorityFromInt(): void
    {
        $priority = new TaskPriority(3);

        $this->assertSame(
            expected: 'high',
            actual: $priority->getKey()
        );

        $this->assertSame(
            expected: 3,
            actual: $priority->getValue()
        );

        $this->assertSame(
            expected: 'Высокий',
            actual: $priority->getLabel()
        );
    }


    public function testTaskPriorityInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new TaskPriority('extra');
    }

    public function testTaskPriorityInvalidFromInt(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new TaskPriority(10);
    }
}
