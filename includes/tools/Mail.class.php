<?php
namespace includes\tools;

/**
 * The mail class  :
 *  - define a common mail canvas from a html template file
 *  - manage e-mail format, sender and receivers
 *  - manage e-mail headers
 *
 * Example :
 *  $mail = new Mail();
 *  $mail->sendSiteMail( 'receiver@mail.com', 'subject', 'The whole message', 'Name Sender', 'sender@mail.com' );
 *
 * Example 2 - File joined : 
 *  $mail = new Mail();
 *  $mail->setHtmlMail('<p>Contenus <br> du message.</p>');
 *  $mail->setTextMail('Contenus du message.');
 *  $mail->setJoinFile( SITE_PATH.'/files/image.jpg', 'Photo', 'img/jpg' );
 *  $mail->sendSiteMail( 'receiver@mail.com', 'subject', 'The whole message', 'Name Sender', 'sender@mail.com' );
 * 
 * 
 * Example 3 - Multiple receivers :
 *  $mail = new Mail();
 *  $receivers = [ 'to' => 'mail@domain.net, mail2@domain.net, mail3@domain.net', 'cc' => 'mail@domain.net, mail2@domain.net, mail3@domain.net' ];
 *  $mail->sendSiteMail( $receivers, 'subject', 'The whole message', 'Name Sender, 'sender@mail.com'' );
 *  
 * @copyright GPL
 * @version 0.3
 */
class Mail {

    private $receiversEmail = null;
    private $receiversEmailTo= null;
    private $receiversEmailCc= null;
    private $senderEmail    = null;
    private $senderName     = null;
    private $subject        = null;
    private $altMixBoudary  = '';
    private $altBoudary     = '';
    private $messageHeader  = '';
    private $messageText    = '';
    private $messageHtml    = '';
    private $messageToSend  = '';
    private $tplPath        = '';
    private $tplPlaceholders= array();
    private $tplContents    = array();
    private $joinFiles      = array();
    private $joinFileContent= '';
    
    function __construct() 
    {
        $this->tplPlaceholders  = array( '/_SUBJECT_/', '/_CHARSET_/', '/_COPYRIGHT_/', '/_YEAR_/', '/_URL_/' );
        
        $this->tplContents      = array( $this->subject, SITE_CHARSET, SITE_TITLE, date( 'Y' ), 'http://'.$_SERVER[ 'HTTP_HOST' ] . SITE_URL );
        
        $this->tplPath = SITE_PATH . '/public/theme/email/email.html.php';
    }
    
    
    public function setHtmlTpl( $tplPath, $tplPlaceholders = array(), $tplContents = array())
    {
        if( count( $tplPlaceholders ) == count( $tplContents ) )
        {
            $this->tplPlaceholders  = $tplPlaceholders;
            $this->tplContents      = $tplContents;
            $this->tplPath          = $tplPath;
        }
    }
    
    public function setHtmlMail( $htmlStr )
    {
        $this->messageHtml .= $htmlStr;
    }
    
    
    private function generateHtmlMessage()
    { 
        if( file_exists( $this->tplPath ) )
        {     
            $tpl = fread( fopen( $this->tplPath, "r" ), filesize( $this->tplPath ) );
            
            array_push( $this->tplPlaceholders, '/_MESSAGE_/' );
            array_push( $this->tplContents, $this->messageHtml );

            $this->messageToSend .= preg_replace( $this->tplPlaceholders, $this->tplContents, $tpl );

        }
        else
        {
          $this->messageToSend .= $this->messageHtml;
        }
        
        return $this->messageToSend;
    }
    
    public function setTextMail( $textStr )
    {
        $this->messageText .= $textStr;
    }
    
    private function generateTextMessage()
    {
        $this->messageToSend .= strip_tags( $this->messageText ) . PHP_EOL;
    }
    
    
    public function setJoinFile( $filePath, $fileName, $fileMimeType )
    {
        $this->joinFiles[] = [ 'path' => $filePath, 'name' => $fileName, 'mimetype' => $fileMimeType ];
    }
    
    
    
    private function sanitizeEmails( $emailListStr )
    {
        $emailListStr = trim( $emailListStr );
        
        return ( filter_var( $emailListStr, FILTER_VALIDATE_EMAIL ) ) ? $emailListStr : ''; 
    }
    
    private function filterEmails( $emailListStr )
    {
        $emails = explode( ';', $emailListStr );
        
        $emailList = [];
        
        if( is_array( $emails ) )
        {
            foreach( $emails as $email )
            {
                $emailsComa = explode( ',', $email );
                
                foreach( $emailsComa as $emailComa )
                {
                    $emailList[] = $this->sanitizeEmails( $emailComa );
                }
            }
        }
        
        return implode( ', ', $emailList );
    
    }
    
    private function generateReceivers()
    {
        $receivers = '';
        
        if( isset( $this->receiversEmail ) )
        {
            if( is_array( $this->receiversEmail ) )
            {
                if( isset( $this->receiversEmail['to'] ) )
                {
                    $this->receiversEmailTo = $this->filterEmails( $this->receiversEmail['to'] );
        
                    $receivers .= ( !empty( $this->receiversEmailTo ) ) ? 'To: ' .$this->receiversEmailTo . PHP_EOL : '';
                }
                
                if( isset( $this->receiversEmail['cc'] ) )
                {
                    $this->receiversEmailCc = $this->filterEmails( $this->receiversEmail['cc'] );
                    
                    $receivers .= ( !empty( $this->receiversEmailCc ) ) ? 'Cc: ' .$this->receiversEmailCc . PHP_EOL : '';
                }
            }
            else
            {
                $this->receiversEmailTo = $this->filterEmails( $this->receiversEmail );
        
                $receivers .= ( !empty( $this->receiversEmailTo ) ) ? 'To: ' .$this->receiversEmailTo . PHP_EOL : '';
            }
        }
        
        return $receivers;
    }
    
    
    private function generateHeaderMessage()
    {
        $this->messageHeader  = 'MIME-Version: 1.0' . PHP_EOL;
        $this->messageHeader .= 'Date: '. date('r') . PHP_EOL;
        $this->messageHeader .= "X-Priority: 3\r\n";
        //$this->messageHeader .= "Subject: {$this->subject}\r\n"; // This line is problematic with some webservers config (i.e. Infomaniak)
        $this->messageHeader .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;
        
        if( isset( $this->senderEmail ) )
        {
            //$this->senderEmail = filter_var($this->senderEmail, FILTER_VALIDATE_EMAIL);
            $this->messageHeader .= 'From: '.$this->senderName . ' <' . $this->senderEmail . '>' . PHP_EOL; // Expediteur
            $this->messageHeader .= 'Sender: <' . $this->senderEmail . '>' . PHP_EOL; 
            $this->messageHeader .= 'Reply-To: <' . $this->senderEmail . '>' . PHP_EOL; // Mail de reponse
            $this->messageHeader .= 'Return-Path:<' . $this->senderEmail . '>' . PHP_EOL;
        }
        else
        {
            $this->messageHeader .= 'From: ' . $this->senderName . PHP_EOL; // Expediteur
        }
        
        $this->messageHeader .= $this->generateReceivers();
        
        if( count( $this->joinFiles ) > 0 )
        {
            $this->messageHeader .= 'Content-Type:  multipart/mixed; boundary="' . $this->altMixBoudary . '"' . PHP_EOL;  
            
            $fp = fopen( $this->joinFiles[ 0 ][ 'path' ].$this->joinFiles[ 0 ][ 'name' ], "rb" );
            $buff = fread( $fp, filesize( $this->joinFiles[ 0 ][ 'path' ].$this->joinFiles[ 0 ][ 'name' ] ) ); 
            fclose( $fp );
            $this->joinFileContent = chunk_split( base64_encode( $buff ) ) . PHP_EOL;
        }
        else
        {
            $this->messageHeader .= 'Content-Type: multipart/alternative; boundary="' . $this->altMixBoudary . '"' . PHP_EOL; 
        }
       
    }
    
    private function generateMessageEnd()
    {
        if( count( $this->joinFiles ) > 0 )
        {
            $fp = fopen( $this->joinFiles[ 0 ][ 'path' ].$this->joinFiles[ 0 ][ 'name' ], "rb" );
            $buff = fread( $fp, filesize( $this->joinFiles[ 0 ][ 'path' ].$this->joinFiles[ 0 ][ 'name' ] ) ); 
            fclose( $fp );
        
            $this->messageToSend .= PHP_EOL . PHP_EOL . "--" . $this->altBoudary . "--" . PHP_EOL;
            $this->messageToSend .= '--'.$this->altMixBoudary . PHP_EOL;
            $this->messageToSend .= 'Content-Type:'.  $this->joinFiles[ 0 ][ 'mimetype' ] . '; name="' . $this->joinFiles[ 0 ][ 'name' ] . '"' . PHP_EOL;
            $this->messageToSend .= 'Content-Transfer-Encoding: base64' . PHP_EOL; 
            $this->messageToSend .= 'Content-Disposition: attachment; filename="' . $this->joinFiles[ 0 ][ 'name' ] . '"' . PHP_EOL. PHP_EOL;
            $this->messageToSend .= chunk_split( base64_encode( $buff ) ) . PHP_EOL;
            $this->messageToSend .= $this->joinFileContent;
        }
        
        $this->messageToSend .= PHP_EOL . '--'.$this->altMixBoudary . "--" . PHP_EOL. PHP_EOL;
    }
    
    private function SendMail()
    {
        if( !empty( $this->receiversEmailTo ) && !empty( $this->subject )&& !empty( $this->messageToSend ) )
        {
            if ( mail( $this->receiversEmailTo, $this->subject, $this->messageToSend, $this->messageHeader ) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    
    /**
     * Sends a message in plain text and an HTML format
     * It use the template display in the folder admin/templates/email.html.php
     * And replace Special words (_MESSAGE_, _SUBJECT_, _CHARSET_, _COPYRIGHT_, _YEAR_, _URL_)
     * with contents
     * 
     * @param array|string $receiverEmail     // E-mail receiver(s). 
     *                                           Could be string list seperated by an ';' or ','. 
     *                                           In case of array use key ['to'] and ['cc']
     *                                           ex. : ['to'=>'mail@domain.net; mail2@domain.net']
     * @param string $subject           // Message Subject
     * @param string $message           // Message
     * @param string $senderName        // Sender's name
     * @param string $senderEmail       // Sender's e-mail
     * 
     * @return boolean
     */
    public function sendSiteMail( $receiversEmail, $subject, $message, $senderName, $senderEmail = NULL )
    {
        $boundary               = uniqid('np');
        $this->receiversEmail   = $receiversEmail;
        $this->senderEmail      = $senderEmail;
        $this->senderName       = $senderName;
        $this->subject          = $subject;
        $this->altBoudary       = 'alt-'.$boundary;
        $this->altMixBoudary    = ( count( $this->joinFiles ) > 0 ) ? 'mixed-'.$boundary : 'alt-'.$boundary;
        $this->messageText      .= strip_tags( $message );
        $this->messageHtml      .= nl2br( $message );
                
        $this->generateHeaderMessage();                
        
        if( count( $this->joinFiles ) > 0 )
        {
            $this->messageToSend .= "\r\n\r\n" . "--" . $this->altMixBoudary . PHP_EOL;
            $this->messageToSend .= 'Content-Type: multipart/alternative; boundary="' . $this->altBoudary . '"'. "\r\n\r\n"; 
        }
        
        $this->messageToSend .= "\r\n\r\n" . "--" . $this->altBoudary . PHP_EOL;
        $this->messageToSend .= 'Content-Type:text/plain; charset="' . SITE_CHARSET . '"' . PHP_EOL;
        $this->messageToSend .= 'Content-Transfer-Encoding: 8bit'. "\r\n\r\n";
        
        $this->generateTextMessage();
        
        $this->messageToSend .= "\r\n\r\n" . "--" . $this->altBoudary . PHP_EOL;
        $this->messageToSend .= 'Content-Type:text/html; charset="' . SITE_CHARSET . '"' . PHP_EOL;
        $this->messageToSend .= 'Content-Transfer-Encoding: 8bit'. "\r\n\r\n";
        
        $this->generateHtmlMessage();
        
        $this->generateMessageEnd();
        
        return ( $this->SendMail() ) ? true : false;
    }    
    
}