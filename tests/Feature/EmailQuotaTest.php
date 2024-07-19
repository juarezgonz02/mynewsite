<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyMail;
use App\User;
use Carbon\Carbon;

class EmailQuotaTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
        
        $cacheKey = "email_quota_daily_limit";
        $quotaLimit = 3;
        $user = new User();
        $user->correo = '00380119@uca.edu.sv';
        $user->nombres = 'Juan';
        $user->apellidos = 'Pérez';
        // $user->utoken->token = '1232130-' ;
        $emailDetails['user'] = $user;


        $emailDetails = [ 
            'email' => '00380119@uca.edu.sv',
            'subject' => 'Verificación de correo electrónico',
            'user' => $user
        ];


        // Simular el tiempo a una hora específica
        Carbon::setTestNow(Carbon::now()->startOfDay());

        // Asegurarse de que la caché esté vacía al inicio
        // Cache::forget($cacheKey);

        // Usar Mail::fake() para evitar el envío real de emails
        Mail::fake();

        // Enviar el primer email
        Mail::to($emailDetails['email'])->send(new VerifyMail($emailDetails));
        $this->assertEquals(1, Cache::get($cacheKey));

        // // Enviar el segundo email
        // Mail::to($emailDetails['email'])->send(new VerifyMail($emailDetails));
        // $this->assertEquals(2, Cache::get($cacheKey));

        // // Enviar el tercer email
        // Mail::to($emailDetails['email'])->send(new VerifyMail($emailDetails));
        // $this->assertEquals(3, Cache::get($cacheKey));

        // // Intentar enviar el cuarto email (debería fallar y liberar el trabajo)
        // Mail::to($emailDetails['email'])->send(new VerifyMail($emailDetails));
        // $this->assertEquals(3, Cache::get($cacheKey));  // La cuota no debería cambiar

        // // Simular el avance de un día
        // Carbon::setTestNow(Carbon::now()->addDay());

        // // Enviar un email después de que el día ha cambiado
        // Mail::to($emailDetails['email'])->send(new VerifyMail($emailDetails));
        // $this->assertEquals(1, Cache::get($cacheKey));

        // // Verificar que los emails se enviaron correctamente
        // Mail::assertSent(VerifyMail::class, 4);  // Deben haberse enviado 4 correos en total


        return true;
    }
}
