<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendancePdfEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $userName;

    public function __construct($pdfContent, $userName)
    {
        $this->pdfContent = $pdfContent;
        $this->userName = $userName;
    }
    

    public function build()
    {
        return $this->subject('Reporte Asistencia - Villas')->view('emails.attendance-pdf')
                    ->attachData($this->pdfContent, 'attendance.pdf');
    }
}
