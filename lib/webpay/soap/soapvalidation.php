<?php
namespace Transbank\Webpay;

class SoapValidation {

    const WSSENS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    const WSSENS_2003 = 'http://schemas.xmlsoap.org/ws/2003/06/secext';
    const WSUNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    const WSSEPFX = 'wsse';
    const WSUPFX = 'wsu';

    private $soapNS, $soapPFX, $certServerPath;
    private $soapDoc = NULL;
    private $envelope = NULL;
    private $SOAPXPath = NULL;
    private $secNode = NULL;
    private $result = FALSE;
    public $signAllHeaders = FALSE;
    public $errorMessage = NULL;

    function __construct($xmlSoap, $certServerPath) {
        $doc = new \DOMDocument("1.0");
        $doc->loadXML($xmlSoap);
        $this->soapDoc = $doc;
        $this->envelope = $doc->documentElement;
        $this->soapNS = $this->envelope->namespaceURI;
        $this->soapPFX = $this->envelope->prefix;

        $this->SOAPXPath = new \DOMXPath($doc);
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS);
        $this->SOAPXPath->registerNamespace('wswsu', self::WSUNS);

        $this->certServerPath = $certServerPath;

        $wsNamespace = $this->locateSecurityHeader();

        if (!empty($wsNamespace)) {
            $this->SOAPXPath->registerNamespace('wswsse', $wsNamespace);
        }
        
        $this->result = $this->process();
    }

    private function locateSecurityHeader($setActor = NULL) {
        $wsNamespace = NULL;
        if ($this->secNode == NULL) {
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header');
            if ($header = $headers->item(0)) {
                $secnodes = $this->SOAPXPath->query('./*[local-name()="Security"]', $header);
                $secnode = NULL;
                foreach ($secnodes AS $node) {
                    $nsURI = $node->namespaceURI;
                    if (($nsURI == self::WSSENS) || ($nsURI == self::WSSENS_2003)) {
                        $actor = $node->getAttributeNS($this->soapNS, 'actor');
                        if (empty($actor) || ($actor == $setActor)) {
                            $secnode = $node;
                            $wsNamespace = $nsURI;
                            break;
                        }
                    }
                }
            }
            $this->secNode = $secnode;
        }
        return $wsNamespace;
    }

    public function processSignature($refNode) {
        $objXMLSecDSig = new XMLSecurityDSig();
        $objXMLSecDSig->idKeys[] = 'wswsu:Id';
        $objXMLSecDSig->idNS['wswsu'] = self::WSUNS;
        $objXMLSecDSig->sigNode = $refNode;

        $objXMLSecDSig->canonicalizeSignedInfo();
        $canonBody = $objXMLSecDSig->canonicalizeBody();

        $retVal = $objXMLSecDSig->validateReference();

        if (!$retVal) {
            throw new \Exception("Validation Failed");
        }

        $key = NULL;
        $objKey = $objXMLSecDSig->locateKey();

        do {
            if (empty($objKey->key)) {
                $x509cert = $this->certServerPath;

                $objKey->loadKey($x509cert, FALSE, TRUE);
                break;

                throw new \Exception("Error loading key to handle Signature");
            }
        } while (0);

        if ($objXMLSecDSig->verify($objKey) &&
                $objXMLSecDSig->compareDigest($canonBody)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function process() {
        if (empty($this->secNode)) {
            return;
        }
        $node = $this->secNode->firstChild;
        while ($node) {
            $nextNode = $node->nextSibling;
            switch ($node->localName) {
                case "Signature":
                    if ($this->processSignature($node)) {
                        if ($node->parentNode) {
                            $node->parentNode->removeChild($node);
                        }
                    } else {

                        return FALSE;
                    }
            }
            $node = $nextNode;
        }
        $this->secNode->parentNode->removeChild($this->secNode);
        $this->secNode = NULL;
        return TRUE;
    }

    function getValidationResult() {
        return $this->result;
    }

}
