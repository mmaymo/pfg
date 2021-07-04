<?php

namespace Tests\Browser;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    protected $currentUrl;
    //use DatabaseMigrations;
    protected static $courseId;

    /**
     *
     * @return void
     * @test
     */
    public function user_can_login()
    {

        $this->browse(function ($browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'admin')
                ->press('ENTRA')
                ->assertPathIs('/dashboard');
        });
    }


    /**
     *
     * @return void
     * @test
     */
   public function teacher_can_create_course()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->press('CREAR CURSO')
                ->assertPathIs('/courses/create')
                ->type('name', 'nombre curso')
                ->type('degree', 'grado')
                ->type('semester', '1')
                ->press('GUARDAR')
                ->pause(2000)
                ->assertPathBeginsWith('/courses');
            $this->currentUrl = $browser->driver->getCurrentURL();
            preg_match('/\d*$/',$this->currentUrl, $matches);
            self::$courseId = $matches[0];
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_add_student()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId) {
            $browser
                ->visit("courses/{$courseId}")
                ->type('registerStudent', 'alumnoDusk@test.com')
                ->pause(1000)
                ->press('AÑADIR ALUMNO')
                ->pause(3000)
                ->assertSee('alumnoDusk@test.com')

            ;
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_create_document_task()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->press('AÑADIR NUEVA TAREA')
                ->type('name', 'tarea dusk document')
                ->type('points', '10')
                ->select('type', 'document')
                ->press('GUARDAR TAREA')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_remove_task()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->click("#taskDraggable > li:nth-child(1) > div > button.delete")
                ->pause(1000)
                ->press('BORRAR TAREA')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_create_quiz_task()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->press('AÑADIR NUEVA TAREA')
                ->type('name', 'tarea dusk quiz')
                ->type('points', '10')
                ->select('type', 'quiz')
                ->pause(1000)
                ->type('question', 'pregunta quiz')
                ->type('answer1', 'respuesta 1')
                ->type('answer2', 'respuesta 2')
                ->type('answer3', 'respuesta 3')
                ->type('answer4', 'respuesta 4')
                ->select('correctAnswer', '1')
                ->press('GUARDAR TAREA')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_create_code_task()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->press('AÑADIR NUEVA TAREA')
                ->type('name', 'tarea dusk code')
                ->type('points', '10')
                ->select('type', 'code')
                ->pause(1000)
                ->press('GUARDAR TAREA')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_create_flashcard_task()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->press('AÑADIR NUEVA TAREA')
                ->type('name', 'tarea dusk flashcard')
                ->type('points', '10')
                ->select('type', 'card')
                ->pause(1000)
                ->type('cardQuestion', 'pregunta flashcard')
                ->type('answer', 'respuesta flashcard')
                ->press('GUARDAR TAREA')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_reorder_task()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->dragUp("#taskDraggable > li:nth-child(2)", 100)
                ->press('GUARDAR ESTE ORDEN')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_edit_task()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->click("#taskDraggable > li:nth-child(1) > div > button.edit")
                ->pause(2000)
                ->type('name', 'tarea dusk modified')
                ->press('GUARDAR TAREA')
                ->pause(1000)
                ->visit("courses/{$courseId}")
                ->pause(3000)
                ->assertSeeIn("#taskDraggable", 'tarea dusk modified');
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_remove_student()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->click("div.registeredStudents .delete")
                ->pause(1000)
                ->press('ELIMINAR AHORA')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_add_teacher()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->type('registerTeacher', 'teacherDusk@test.com')
                ->pause(1000)
                ->press('AÑADIR PROFESOR')
                ->pause(2000)
                ->assertPathIs("/courses/{$courseId}");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_remove_course()
    {
        $courseId = self::$courseId;
        $this->browse(function (Browser $browser) use($courseId){
            $browser
                ->visit("courses/{$courseId}")
                ->press('BORRAR CURSO')
                ->pause(1000)
                ->press('BORRAR AHORA')
                ->pause(2000)
                ->assertPathIs("/dashboard");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function teacher_can_add_registered_student()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit("courses/1")
                ->select('addRegisteredStudent', 'test@test.com')
                ->press('AÑADIR SELECCIÓN')
                ->pause(1000)
                ->scrollTo('.registeredStudents')
                ->assertSee('test@test.com');
        });
    }
    /**
     *
     * @return void
     * @test
     */
    public function enrolled_user_can_see_course()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/courses/1/tasks/1')
                ->pause(2000)
                ->assertSee('DISEÑO DEL SISTEMA OPERATIVO UNIX')
                ->press('SUMA Y SIGUE')
                ->pause(2000)
                ->assertSee('5 PUNTOS')
                ->assertSee('0.4%');
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function enrolled_user_can_reset_course()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/courses/1')
                ->click("div.registeredStudents .reset")
                ->pause(1000)
                ->press('ELIMINAR AHORA')
                ->pause(2000)
                ->assertSee("0 Puntos")
                ->assertSee("Progreso: 0.0%");
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function enrolled_user_can_do_quiz()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/courses/1/tasks/16')
                ->pause(2000)
                ->radio('answer1','1')
                ->press('ENVIAR RESPUESTA')
                ->pause(2000)
                ->assertSee('Esta respuesta no es correcta, la respuesta correcta está recuadrada en verde')
                ->visit('/courses/1/tasks/17')
                ->pause(2000)
                ->radio('answer1','1')
                ->press('ENVIAR RESPUESTA')
                ->pause(2000)
                ->assertSee('Esta es la respuesta correcta');
        });
    }

    /**
     *
     * @return void
     * @test
     */
    public function enrolled_user_can_do_flashcard()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/courses/1/flash')
                ->pause(2000)
                ->assertSee('Pregunta o concepto para memorizar')
                ->press('VER RESPUESTA')
                ->pause(2000)
                ->assertSee('La respuesta a lo que hemos preguntado');
        });
    }

}
