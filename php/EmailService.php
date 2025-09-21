<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configureMailer();
    }
    
    private function loadEnvConfig() {
        // Load .env file if exists
        if (file_exists(__DIR__ . '/../.env')) {
            $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') === false) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
    }
    
    private function configureMailer() {
        $this->loadEnvConfig();
        
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['SMTP_USERNAME'] ?? '';
            $this->mail->Password = $_ENV['SMTP_PASSWORD'] ?? '';
            
            // Configure port and encryption based on the port number
            $port = intval($_ENV['SMTP_PORT'] ?? 587);
            $this->mail->Port = $port;
            
            if ($port == 465) {
                // For port 465, use implicit TLS (SMTPS)
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                // For port 587 (or others), use explicit TLS (STARTTLS)
                $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            // Connection timeout and debugging
            $this->mail->Timeout = 10;
            $this->mail->SMTPDebug = 0; // Set to 2 for debugging
            $this->mail->Debugoutput = 'error_log';
            
            // Default From address
            $fromEmail = $_ENV['FROM_EMAIL'] ?? 'noreply@nexon.com';
            $fromName = $_ENV['FROM_NAME'] ?? 'Nexon Digital Marketing';
            $this->mail->setFrom($fromEmail, $fromName);
            
            // Configure for better deliverability
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            error_log("Email configuration error: " . $e->getMessage());
        }
    }
    
    public function sendContactForm($formData) {
        try {
            // Recipients
            $toEmail = $_ENV['CONTACT_EMAIL'] ?? 'lehuuphuc.ht2016@gmail.com';
            $this->mail->addAddress($toEmail);
            
            // Email content
            $this->mail->Subject = 'Contact Form: ' . htmlspecialchars($formData['subject']);
            
            // HTML Body
            $htmlBody = $this->generateContactFormHTML($formData);
            $this->mail->Body = $htmlBody;
            
            // Plain text version
            $textBody = $this->generateContactFormText($formData);
            $this->mail->AltBody = $textBody;
            
            // Set reply-to to customer email
            if (!empty($formData['email'])) {
                $this->mail->addReplyTo($formData['email'], $formData['firstname'] . ' ' . $formData['lastname']);
            }
            
            if (!$this->mail->send()) {
                $errorInfo = $this->mail->ErrorInfo;
                error_log("Contact form email error: " . $errorInfo);
                error_log("SMTP Debug Info: Host=" . $this->mail->Host . ", Port=" . $this->mail->Port . ", Secure=" . $this->mail->SMTPSecure);
                return ['success' => false, 'message' => 'Failed to send message. Please try again.'];
            }
            
            $this->mail->clearAddresses();
            $this->mail->clearReplyTos();
            
            return ['success' => true, 'message' => 'Message sent successfully!'];
            
        } catch (Exception $e) {
            error_log("Contact form email error: " . $e->getMessage());
            error_log("SMTP Error Info: " . $this->mail->ErrorInfo);
            return ['success' => false, 'message' => 'Failed to send message. Please try again.'];
        }
    }
    
    public function sendNewsletterConfirmation($email) {
        try {
            // Send to admin
            $adminEmail = $_ENV['NEWSLETTER_EMAIL'] ?? 'Hello@nexon.com';
            $this->mail->addAddress($adminEmail);
            
            $this->mail->Subject = 'New Newsletter Subscription';
            $this->mail->Body = "<h3>New Newsletter Subscription</h3><p>Email: <strong>{$email}</strong></p>";
            $this->mail->AltBody = "New Newsletter Subscription\nEmail: {$email}";
            
            if (!$this->mail->send()) {
                $errorInfo = $this->mail->ErrorInfo;
                error_log("Newsletter email error: " . $errorInfo);
                error_log("SMTP Debug Info: Host=" . $this->mail->Host . ", Port=" . $this->mail->Port . ", Secure=" . $this->mail->SMTPSecure);
                return ['success' => false, 'message' => 'Failed to subscribe. Please try again.'];
            }
            
            $this->mail->clearAddresses();
            
            // Optionally send confirmation to subscriber
            $this->sendSubscriberWelcome($email);
            
            return ['success' => true, 'message' => 'Thank you for subscribing!'];
            
        } catch (Exception $e) {
            error_log("Newsletter email error: " . $e->getMessage());
            error_log("SMTP Error Info: " . $this->mail->ErrorInfo);
            return ['success' => false, 'message' => 'Failed to subscribe. Please try again.'];
        }
    }
    
    private function sendSubscriberWelcome($email) {
        try {
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Welcome to Nexon Newsletter!';
            
            $htmlBody = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                <h2 style="color: #333;">Welcome to Nexon Newsletter!</h2>
                <p>Thank you for subscribing to our newsletter. You\'ll now receive:</p>
                <ul>
                    <li>Latest digital marketing insights</li>
                    <li>Industry trends and tips</li>
                    <li>Exclusive content and offers</li>
                </ul>
                <p>Best regards,<br>The Nexon Team</p>
            </div>';
            
            $this->mail->Body = $htmlBody;
            $this->mail->AltBody = "Welcome to Nexon Newsletter!\n\nThank you for subscribing. You'll receive latest digital marketing insights, industry trends, and exclusive content.\n\nBest regards,\nThe Nexon Team";
            
            $this->mail->send();
            $this->mail->clearAddresses();
            
        } catch (Exception $e) {
            error_log("Welcome email error: " . $e->getMessage());
        }
    }
    
    private function generateContactFormHTML($data) {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <h2 style="color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px;">New Contact Form Submission</h2>
            
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; font-weight: bold; width: 150px;">First Name:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($data['firstname']) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; font-weight: bold;">Last Name:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($data['lastname']) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; font-weight: bold;">Email:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;"><a href="mailto:' . htmlspecialchars($data['email']) . '">' . htmlspecialchars($data['email']) . '</a></td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; font-weight: bold;">Subject:</td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">' . htmlspecialchars($data['subject']) . '</td>
                </tr>
            </table>
            
            <div style="margin: 20px 0;">
                <h3 style="color: #333;">Message:</h3>
                <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #4CAF50; margin: 10px 0;">
                    ' . nl2br(htmlspecialchars($data['message'])) . '
                </div>
            </div>
            
            <p style="color: #666; font-size: 12px; margin-top: 30px;">This email was sent from the Nexon website contact form.</p>
        </div>';
    }
    
    private function generateContactFormText($data) {
        return "New Contact Form Submission\n\n" .
               "First Name: " . $data['firstname'] . "\n" .
               "Last Name: " . $data['lastname'] . "\n" .
               "Email: " . $data['email'] . "\n" .
               "Subject: " . $data['subject'] . "\n\n" .
               "Message:\n" . $data['message'] . "\n\n" .
               "This email was sent from the Nexon website contact form.";
    }
}
?>