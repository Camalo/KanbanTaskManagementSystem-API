<?

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use Kamalo\KanbanTaskManagementSystem\Domain\ValueObject\FullName;
use PHPUnit\Framework\TestCase;

class FullNameTest extends TestCase
{
    public function testFullNameWithMiddleName(): void
    {
        $fullName = new FullName(
            firstName: 'Павел',
            middleName: 'Александрович',
            lastName: 'Крамской'
        );

        $expectedFullName = 'Крамской Павел Александрович';

        $this->assertSame($expectedFullName, $fullName->getFullName());
    }

    public function testFullNameWithoutMiddleName(): void
    {
        $fullName = new FullName(
            firstName: 'Алсу',
            lastName: 'Камалова',
            middleName: null
        );

        $expectedFullName = 'Камалова Алсу';

        $this->assertSame($expectedFullName, $fullName->getFullName());
    }

    public function testFirstNameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: '',
            middleName: 'Александрович',
            lastName: 'Крамской'
        );
    }


    public function testFirstNameExceedMaxLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: 'ПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавелПавел',
            middleName: 'Александрович',
            lastName: 'Крамской'
        );
    }

    public function testFirstNameCannotContainLeadingOrTrailingSpaces(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: ' Павел ',
            middleName: 'Александрович',
            lastName: 'Крамской'
        );
    }

    public function testMiddleNameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: 'Павел',
            middleName: '',
            lastName: 'Крамской'
        );
    }

    public function testMiddleNameExceedMaxLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: 'Павел',
            middleName: 'АлександровичАлександровичАлександровичАлександровичАлександровичАлександровичАлександровичАлександрович',
            lastName: 'Крамской'
        );
    }

    public function testMiddleNameCannotContainLeadingOrTrailingSpaces(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: 'Павел',
            middleName: 'Александрович ',
            lastName: 'Крамской '
        );
    }

    public function testLastNameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: 'Павел',
            middleName: 'Александрович',
            lastName: ''
        );
    }

    public function testLastNameExceedMaxLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: 'Павел',
            middleName: 'Александрович',
            lastName: 'КрамскойКрамскойКрамскойКрамскойКрамскойКрамскойКрамскойКрамскойКрамскойКрамскойКрамскойКрамскойКрамской'
        );
    }

    public function testLastNameCannotContainLeadingOrTrailingSpaces(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new FullName(
            firstName: 'Павел',
            middleName: 'Александрович',
            lastName: ' Крамской '
        );
    }
}
