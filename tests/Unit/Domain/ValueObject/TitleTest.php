<?

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\Title;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    public function testCannotBeEmptyString(): void
    {
        $value = '';

        $this->expectException(\InvalidArgumentException::class);

        new Title($value);
    }

    public function testExceedMaxLength(): void
    {
        $value = "Приступая к описанию недавних и столь странных событий, происшедших в нашем, 
        доселе ничем не отличавшемся городе, я принужден, по неумению моему, начать несколько издалека, 
        а именно некоторыми биографическими подробностями о талантливом и многочтимом Степане Трофимовиче Верховенском.";

        $this->expectException(\InvalidArgumentException::class);

        new Title($value);
    }
}
