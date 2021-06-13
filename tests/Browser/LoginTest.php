<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    //use DatabaseMigrations;
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
        /*
        3. crear curso
        4. crear tarea documento
        5. crear tarea quiz
        6. crear tarea code
        7. crear tarea flashcard
        8. añadir alumno a curso
        9. quitar alumno de curso
        10. reordenar tareas en curso
        11. editar tarea
        12. avanzar en curso
        13. sumar puntos y progreso en avance
        14. realizar tarea quiz
        15. realizar tarea multiquiz
        16. realizar tarea flashcard
        17. realizar tarea code
        18. Añadir alumnos en bloque
        19. Añadir profesor
        20. Resetear puntos*/

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
                ->assertPathBeginsWith('/courses');
        });
    }
}
