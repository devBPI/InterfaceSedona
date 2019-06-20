<?php


use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Session;

trait W3CValidationContext
{
    /**
     * @var string
     */
    protected $url = 'https://validator.w3.org/nu/?out=json';

    /**
     * @var array;
     */
    protected $warnings;

    /**
     * @var array;
     */
    protected $errors;


    /**
     * Returns Mink session.
     *
     * @param string|null $name name of the session OR active session will be used
     *
     * @return Session
     */
    abstract public function getSession($name = null);

    /**
     * @When /^I check source code on W3C validation service$/
     */
    public function iCheckSourceCodeOnW3CValidationService() {

        $this->resetErrors();
        $this->resetWarnings();

        // get compressed source code of page
        $compressedSourceCode = $this->compressMarkup($this->getSession()->getPage()->getContent());

        $this->validate($compressedSourceCode);
    }

    /**
     * @Then /^I should see (.*) W3C validation errors$/
     */
    public function iShouldSeeNW3CValidationErrors($countStr) {
        $expectedErrorCount = $this->getNumber($countStr);
        $actualErrorCount = count($this->errors);
        if ($actualErrorCount != $expectedErrorCount) {
            throw new ExpectationException("Expected errors: {$expectedErrorCount}. Actual found errors: {$actualErrorCount}." . ($actualErrorCount ? (" Detailed list of errors: \n" . implode("\n---------------------------------------------------------\n", $this->errors)) : ''), $this->getSession());
        }
    }

    /**
     * @Given /^I should see (.*) W3C validation warnings$/
     */
    public function iShouldSeeNW3CValidationWarnings($countStr) {
        $expectedWarningCount = $this->getNumber($countStr);
        $actualWarningCount = count($this->errors);
        if ($actualWarningCount != $expectedWarningCount) {
            throw new ExpectationException("Expected warnings: {$expectedWarningCount}. Actual found warnings: {$actualWarningCount}." . ($actualWarningCount ? (" Detailed list of warnings: \n" . implode("\n---------------------------------------------------------\n", $this->warnings)) : ''), $this->getSession());
        }
    }

    /**
     * @param string $markupCode
     * @return string
     */
    protected function compressMarkup($markupCode) {
        // compressing all white spaces
        $markupCode = preg_replace('/(\r\n|\n|\r|\t)/im', '', $markupCode);
        $markupCode = preg_replace('/\s+/m', ' ', $markupCode);

        return $markupCode;
    }

    /**
     * @param $str
     * @return int
     */
    protected function getNumber($str) {
        switch (trim($str)) {
            case 'no':
                $result = 0;
                break;
            default:
                $result = intval($str);
                break;
        }

        return $result;
    }

    /**
     * @return void
     */
    protected function resetWarnings() {
        $this->warnings = array();
    }

    /**
     * @return void
     */
    protected function resetErrors() {
        $this->errors = array();
    }

    /**
     * External call to the W3C Validation API, using curl.
     *
     * @param $html
     * @throws ExpectationException
     */
    protected function validate($html) {
        $resource = curl_init($this->url);
        curl_setopt($resource, CURLOPT_USERAGENT, 'curl');
        curl_setopt($resource, CURLOPT_POST, true);
        curl_setopt($resource, CURLOPT_HTTPHEADER, array('Content-Type: text/html; charset=utf-8'));
        curl_setopt($resource, CURLOPT_POSTFIELDS, $html);
        curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($resource), TRUE);
        if ($response === NULL || !is_array($response)) {
            throw new ExpectationException('Could not parse W3C JSON API output', $this->getSession());
        }
        if (array_key_exists('messages', $response)) {
            foreach ($response['messages'] as $message) {
                switch ($message['type']) {
                    case 'info':
                        if (array_key_exists('subType', $message) && $message['subType'] === 'warning') {
                            $this->warnings[] = "
message: {$message['message']}
line: {$message['lastLine']}
column: {$message['lastColumn']}
extract: {$message['extract']}
";
                        }
                        break;
                    case 'error':
                        $this->errors[] = "
message: {$message['message']}
line: {$message['lastLine']}
column: {$message['lastColumn']}
extract: {$message['extract']}
";
                        break;
                    default:
                }

            }
        }
    }
}
