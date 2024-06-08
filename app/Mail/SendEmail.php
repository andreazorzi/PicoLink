<?php

namespace App\Mail;
  
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Attachment;
use Illuminate\Support\Facades\App;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachments;
use Illuminate\Contracts\Queue\ShouldQueue;
  
class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $mail_data;
  
    /**
     * Create a new message instance.
     */
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
    }
  
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $from = $this->mail_data["sender"] ?? [
            "email" => config("mail.from.address"),
            "name" => config("mail.from.name")
        ];
        
        $to = [];
        $cc = [];
        $bcc = [];
        
        if(!App::environment('local')){
            foreach($this->mail_data["receivers"] ?? [] as $recipient){
                if(empty($recipient["email"])) continue;
                
                $to[] = new Address($recipient["email"], $recipient["name"] ?? null);
            }
            
            foreach($this->mail_data["cc"] ?? [] as $recipient){
                if(empty($recipient["email"])) continue;
                
                $cc[] = new Address($recipient["email"], $recipient["name"] ?? null);
            }
            
            foreach($this->mail_data["bcc"] ?? [] as $recipient){
                if(empty($recipient["email"])) continue;
                
                $bcc[] = new Address($recipient["email"], $recipient["name"] ?? null);
            }
        }
        else{
            $to[] = new Address(config("mail.test.address"));
        }
        
        return new Envelope(
            from: new Address($from["email"], $from["name"] ?? null),
            to: $to,
            cc: $cc,
            bcc: $bcc,
            subject: $this->mail_data["subject"] ?? "",
        );
    }
  
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->mail_data["body"]["view"] ?? "emails.test",
            with: [
                "parameters" => $this->mail_data["body"]["parameters"] ?? []
            ]
        );
    }
  
    /**
     * Get the attachments for the message.
     *
     * @return array

     */
    public function attachments(): array
    {
        $attachments = [];
        
        foreach($this->mail_data["attachments"] ?? [] as $attachment){
            $file = Attachment::fromPath($attachment["path"]);
            if(!empty($attachment["name"])) $file->as($attachment["name"]);
            
            $attachments[] = $file;
        }
        
        return $attachments;
    }
}