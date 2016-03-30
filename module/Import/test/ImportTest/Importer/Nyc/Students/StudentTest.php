<?php

namespace ImportTest\Importer\Nyc\Students;

use Import\Importer\Nyc\ClassRoom\ClassRoom;
use Import\Importer\Nyc\Exception\InvalidStudentException;
use Import\Importer\Nyc\Students\Student;
use \PHPUnit_Framework_TestCase as TestCase;
use User\Adult;
use User\Child;

/**
 * Exception StudentTest
 *
 * ${CARET}
 */
class StudentTest extends TestCase
{
    public function testItShouldValidateOnGoodStudent()
    {
        $student = new Student();
        $student->setFirstName('Chuck');
        $student->setLastName('Reeves');
        $student->setBirthday(new \DateTime('1982-05-13 23:38:00'));
        $student->setStudentId('foo-bar');

        $this->assertTrue($student->isValid(), 'Student was not validated as good');
        $this->assertTrue($student->isNew(), 'Student was not reported as new');
        $student->setUser(new Child());
        $this->assertFalse($student->isNew(), 'Student was reported as new when user set');

        $this->assertFalse($student->hasClassAssigned(), 'The student should not have a classroom set');

        $student->setClassRoom(new ClassRoom('History of the world', 'hist101'));
        $this->assertTrue($student->hasClassAssigned(), 'The student should now report as having a classroom assigned');
    }

    public function testItShouldThrowExceptionWhenTryingToSetAdultAsUser()
    {
        $this->setExpectedException(InvalidStudentException::class);
        $student = new Student();
        $student->setUser(new Adult());
    }
}
