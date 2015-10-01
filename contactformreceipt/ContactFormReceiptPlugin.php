<?php

namespace Craft;


class ContactFormReceiptPlugin extends BasePlugin {
    /**
     * Returns the plugin’s version number.
     *
     * @return string The plugin’s version number.
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * Returns the plugin developer’s name.
     *
     * @return string The plugin developer’s name.
     */
    public function getDeveloper()
    {
        return 'Foobar';
    }

    /**
     * Returns the plugin developer’s URL.
     *
     * @return string The plugin developer’s URL.
     */
    public function getDeveloperUrl()
    {
        return 'http://www.foobar.com';
    }

    /**
     * Set up event listener and send a receipt email
     */
    public function init() {
        craft()->on('contactForm.beforeSend', function(ContactFormEvent $event) {
            $message = $event->params['message'];
            $receiptReceiverEmail = $message->fromEmail;

            $receipt = new EmailModel();
            $emailSettings = craft()->email->getSettings();

            $receipt->fromEmail = $emailSettings['emailAddress'];
            $receipt->sender = $emailSettings['emailAddress'];
            $receipt->fromName = 'Some company';
            $receipt->toEmail = $receiptReceiverEmail;
            $locale = craft()->getLocale();
            $receipt->subject = $this->getSubject($locale->getId());

            $receipt->body = $this->getBody($locale->getId());

            craft()->email->sendEmail($receipt);
        });
    }

    protected function getSubject($locale = 'nb_no') {
        return 'Thank you for your inquiry';
    }
    protected function getBody($locale = 'nb_no') {
        return "Thank you for your inquery.<br><br>This is an automatic response to your inquery.<br>We will be in touch soon.<br><br>Kind regards,<br>Foobar";
    }
}