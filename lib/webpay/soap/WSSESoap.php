<?php
namespace Transbank\Webpay;

function sortAndAddAttrs($element, $arAtts) {
    $newAtts = array();
    foreach ($arAtts AS $attnode) {
        $newAtts[$attnode->nodeName] = $attnode;
    }
    ksort($newAtts);
    foreach ($newAtts as $attnode) {
        $element->setAttribute($attnode->nodeName, $attnode->nodeValue);
    }
}

/* helper function */

function canonical($tree, $element, $withcomments) {
    if ($tree->nodeType != XML_DOCUMENT_NODE) {
        $dom = $tree->ownerDocument;
    } else {
        $dom = $tree;
    }
    if ($element->nodeType != XML_ELEMENT_NODE) {
        if ($element->nodeType == XML_DOCUMENT_NODE) {
            foreach ($element->childNodes AS $node) {
                canonical($dom, $node, $withcomments);
            }
            return;
        }
        if ($element->nodeType == XML_COMMENT_NODE && !$withcomments) {
            return;
        }
        $tree->appendChild($dom->importNode($element, TRUE));
        return;
    }
    $arNS = array();
    if ($element->namespaceURI != "") {
        if ($element->prefix == "") {
            $elCopy = $dom->createElementNS($element->namespaceURI, $element->nodeName);
        } else {
            $prefix = $tree->lookupPrefix($element->namespaceURI);
            if ($prefix == $element->prefix) {
                $elCopy = $dom->createElementNS($element->namespaceURI, $element->nodeName);
            } else {
                $elCopy = $dom->createElement($element->nodeName);
                $arNS[$element->namespaceURI] = $element->prefix;
            }
        }
    } else {
        $elCopy = $dom->createElement($element->nodeName);
    }
    $tree->appendChild($elCopy);

    /* Create DOMXPath based on original document */
    $xPath = new DOMXPath($element->ownerDocument);

    /* Get namespaced attributes */
    $arAtts = $xPath->query('attribute::*[namespace-uri(.) != ""]', $element);

    /* Create an array with namespace URIs as keys, and sort them */
    foreach ($arAtts AS $attnode) {
        if (array_key_exists($attnode->namespaceURI, $arNS) &&
            ($arNS[$attnode->namespaceURI] == $attnode->prefix)) {
            continue;
        }
        $prefix = $tree->lookupPrefix($attnode->namespaceURI);
        if ($prefix != $attnode->prefix) {
            $arNS[$attnode->namespaceURI] = $attnode->prefix;
        } else {
            $arNS[$attnode->namespaceURI] = NULL;
        }
    }
    if (count($arNS) > 0) {
        asort($arNS);
    }

    /* Add namespace nodes */
    foreach ($arNS AS $namespaceURI => $prefix) {
        if ($prefix != NULL) {
            $elCopy->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:" . $prefix, $namespaceURI);
        }
    }
    if (count($arNS) > 0) {
        ksort($arNS);
    }

    /* Get attributes not in a namespace, and then sort and add them */
    $arAtts = $xPath->query('attribute::*[namespace-uri(.) = ""]', $element);
    sortAndAddAttrs($elCopy, $arAtts);

    /* Loop through the URIs, and then sort and add attributes within that namespace */
    foreach ($arNS as $nsURI => $prefix) {
        $arAtts = $xPath->query('attribute::*[namespace-uri(.) = "' . $nsURI . '"]', $element);
        sortAndAddAttrs($elCopy, $arAtts);
    }

    foreach ($element->childNodes AS $node) {
        canonical($elCopy, $node, $withcomments);
    }
}

function getIssuerName($X509Cert) {
    $cert = $X509Cert;
    $cert_as_array = openssl_x509_parse($cert);
    $name = $cert_as_array['name'];
    $name = str_replace("/", ",", $name);
    $name = substr($name, 1, strlen($name));
    return $name;
}

function getSerialNumber($X509Cert) {
    $cert = $X509Cert;
    $cert_as_array = openssl_x509_parse($cert);
    // To prevent OpenSSL 1.1 issue when the serial number sometimes comes as an hex, we alway use the serialNumberHex
    // and then we transform it again to integer.
    $serialNumberHex = $cert_as_array['serialNumberHex'];
    $serialNumber = x64toSignedInt($serialNumberHex);
    return $serialNumber;
}

// Author: @ecrode https://stackoverflow.com/questions/1273484/large-hex-values-with-php-hexdec
function x64toSignedInt($hexNumber){
    $leftHalf = hexdec(substr($hexNumber,0,8));
    $rightHalf = hexdec(substr($hexNumber,8,8));
    return (int) ($leftHalf << 32) | $rightHalf;
}

/*
  $element - DOMElement for which to produce the canonical version of
  $exclusive - boolean to indicate exclusive canonicalization (must pass TRUE)
  $withcomments - boolean indicating wether or not to include comments in canonicalized form
 */

function C14NGeneral($element, $exclusive = FALSE, $withcomments = FALSE) {
    /* IF PHP 5.2+ then use built in canonical functionality */
    $php_version = explode('.', PHP_VERSION);
    if (($php_version[0] > 5) || ($php_version[0] == 5 && $php_version[1] >= 2)) {
        return $element->C14N($exclusive, $withcomments);
    }

    /* Must be element or document */
    if (!$element instanceof DOMElement && !$element instanceof DOMDocument) {
        return NULL;
    }
    /* Currently only exclusive XML is supported */
    if ($exclusive == FALSE) {
        throw new Exception("Only exclusive canonicalization is supported in this version of PHP");
    }

    $copyDoc = new DOMDocument();
    canonical($copyDoc, $element, $withcomments);
    return $copyDoc->saveXML($copyDoc->documentElement, LIBXML_NOEMPTYTAG);
}

/**
 * WSSESoap.php
 * 
 * Copyright (c) 2010, Robert Richards <rrichards@ctindustries.net>. 
 * All rights reserved. 
 * 
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions 
 * are met: 
 * 
 *   * Redistributions of source code must retain the above copyright 
 *     notice, this list of conditions and the following disclaimer. 
 * 
 *   * Redistributions in binary form must reproduce the above copyright 
 *     notice, this list of conditions and the following disclaimer in 
 *     the documentation and/or other materials provided with the 
 *     distribution. 
 * 
 *   * Neither the name of Robert Richards nor the names of his 
 *     contributors may be used to endorse or promote products derived 
 *     from this software without specific prior written permission. 
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS 
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE 
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER 
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT 
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN 
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE. 
 * 
 * @author     Robert Richards <rrichards@ctindustries.net> 
 * @copyright  2007-2010 Robert Richards <rrichards@ctindustries.net> 
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License 
 * @version    1.1.0-dev 
 */
class WSSESoap {

    const WSSENS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    const WSUNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    const WSUNAME = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0';
    const WSSEPFX = 'wsse';
    const WSUPFX = 'wsu';

    private $soapNS, $soapPFX;
    private $soapDoc = NULL;
    private $envelope = NULL;
    private $SOAPXPath = NULL;
    private $secNode = NULL;
    public $signAllHeaders = FALSE;

    private function locateSecurityHeader($bMustUnderstand = TRUE, $setActor = NULL) {
        if ($this->secNode == NULL) {
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header');
            $header = $headers->item(0);
            if (!$header) {
                $header = $this->soapDoc->createElementNS($this->soapNS, $this->soapPFX . ':Header');
                $this->envelope->insertBefore($header, $this->envelope->firstChild);
            }
            $secnodes = $this->SOAPXPath->query('./wswsse:Security', $header);
            $secnode = NULL;
            foreach ($secnodes AS $node) {
                $actor = $node->getAttributeNS($this->soapNS, 'actor');
                if ($actor == $setActor) {
                    $secnode = $node;
                    break;
                }
            }
            if (!$secnode) {
                $secnode = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':Security');
                $header->appendChild($secnode);
                if ($bMustUnderstand) {
                    $secnode->setAttributeNS($this->soapNS, $this->soapPFX . ':mustUnderstand', '1');
                }
                if (!empty($setActor)) {
                    $ename = 'actor';
                    if ($this->soapNS == 'http://www.w3.org/2003/05/soap-envelope') {
                        $ename = 'role';
                    }
                    $secnode->setAttributeNS($this->soapNS, $this->soapPFX . ':' . $ename, $setActor);
                }
            }
            $this->secNode = $secnode;
        }
        return $this->secNode;
    }

    public function __construct($doc, $bMustUnderstand = TRUE, $setActor = NULL) {
        $this->soapDoc = $doc;
        $this->envelope = $doc->documentElement;
        $this->soapNS = $this->envelope->namespaceURI;
        $this->soapPFX = $this->envelope->prefix;
        $this->SOAPXPath = new \DOMXPath($doc);
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS);
        $this->SOAPXPath->registerNamespace('wswsse', WSSESoap::WSSENS);
        $this->locateSecurityHeader($bMustUnderstand, $setActor);
    }

    public function addTimestamp($secondsToExpire = 3600) {

        /* Add the WSU timestamps */
        $security = $this->locateSecurityHeader();

        $timestamp = $this->soapDoc->createElementNS(WSSESoap::WSUNS, WSSESoap::WSUPFX . ':Timestamp');
        $security->insertBefore($timestamp, $security->firstChild);
        $currentTime = time();
        $created = $this->soapDoc->createElementNS(WSSESoap::WSUNS, WSSESoap::WSUPFX . ':Created', gmdate("Y-m-d\TH:i:s", $currentTime) . 'Z');
        $timestamp->appendChild($created);
        if (!is_null($secondsToExpire)) {
            $expire = $this->soapDoc->createElementNS(WSSESoap::WSUNS, WSSESoap::WSUPFX . ':Expires', gmdate("Y-m-d\TH:i:s", $currentTime + $secondsToExpire) . 'Z');
            $timestamp->appendChild($expire);
        }
    }

    public function addUserToken($userName, $password = NULL, $passwordDigest = FALSE) {
        if ($passwordDigest && empty($password)) {
            throw new \Exception("Cannot calculate the digest without a password");
        }

        $security = $this->locateSecurityHeader();

        $token = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':UsernameToken');
        $security->insertBefore($token, $security->firstChild);

        $username = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':Username', $userName);
        $token->appendChild($username);

        /* Generate nonce - create a 256 bit session key to be used */
        $objKey = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
        $nonce = $objKey->generateSessionKey();
        unset($objKey);
        $createdate = gmdate("Y-m-d\TH:i:s") . 'Z';

        if ($password) {
            $passType = WSSESoap::WSUNAME . '#PasswordText';
            if ($passwordDigest) {
                $password = base64_encode(sha1($nonce . $createdate . $password, true));
                $passType = WSSESoap::WSUNAME . '#PasswordDigest';
            }
            $passwordNode = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':Password', $password);
            $token->appendChild($passwordNode);
            $passwordNode->setAttribute('Type', $passType);
        }

        $nonceNode = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':Nonce', base64_encode($nonce));
        $token->appendChild($nonceNode);

        $created = $this->soapDoc->createElementNS(WSSESoap::WSUNS, WSSESoap::WSUPFX . ':Created', $createdate);
        $token->appendChild($created);
    }

    public function addBinaryToken($cert, $isPEMFormat = TRUE, $isDSig = TRUE) {
        $security = $this->locateSecurityHeader();
        $data = XMLSecurityDSig::get509XCert($cert, $isPEMFormat);

        $token = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':BinarySecurityToken', $data);
        $security->insertBefore($token, $security->firstChild);

        $token->setAttribute('EncodingType', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary');
        $token->setAttributeNS(WSSESoap::WSUNS, WSSESoap::WSUPFX . ':Id', XMLSecurityDSig::generate_GUID());
        $token->setAttribute('ValueType', 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3');

        return $token;
    }

    public function attachTokentoSig($token) {
        if (!($token instanceof \DOMElement)) {
            throw new \Exception('Invalid parameter: BinarySecurityToken element expected');
        }
        $objXMLSecDSig = new XMLSecurityDSig();
        if ($objDSig = $objXMLSecDSig->locateSignature($this->soapDoc)) {
            $tokenURI = '#' . $token->getAttributeNS(WSSESoap::WSUNS, "Id");
            $this->SOAPXPath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
            $query = "./secdsig:KeyInfo";
            $nodeset = $this->SOAPXPath->query($query, $objDSig);
            $keyInfo = $nodeset->item(0);
            if (!$keyInfo) {
                $keyInfo = $objXMLSecDSig->createNewSignNode('KeyInfo');
                $objDSig->appendChild($keyInfo);
            }

            $tokenRef = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':SecurityTokenReference');
            $keyInfo->appendChild($tokenRef);
            $reference = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':Reference');
            $reference->setAttribute("URI", $tokenURI);
            $reference->setAttribute("ValueType", 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3');
            $tokenRef->appendChild($reference);
        } else {
            throw new \Exception('Unable to locate digital signature');
        }
    }

    public function addIssuerSerial($X509Cert) {

        $name = getIssuerName($X509Cert);
        $serialNumber = getSerialNumber($X509Cert);

        $objXMLSecDSig = new XMLSecurityDSig();
        if ($objDSig = $objXMLSecDSig->locateSignature($this->soapDoc)) {
            $this->SOAPXPath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
            $query = "./secdsig:KeyInfo";
            $nodeset = $this->SOAPXPath->query($query, $objDSig);
            $keyInfo = $nodeset->item(0);
            if (!$keyInfo) {
                $keyInfo = $objXMLSecDSig->createNewSignNode('KeyInfo');
                $objDSig->appendChild($keyInfo);
            }

            $tokenRef = $this->soapDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':SecurityTokenReference');
            $keyInfo->appendChild($tokenRef);
            $x509Data = $objXMLSecDSig->createNewSignNode("X509Data");
            $x509IssuerSerial = $objXMLSecDSig->createNewSignNode("X509IssuerSerial");
            $x509Data->appendChild($x509IssuerSerial);

            $x509IssuerName = $objXMLSecDSig->createNewSignNode("X509IssuerName", $name);
            $x509SerialNumber = $objXMLSecDSig->createNewSignNode("X509SerialNumber", $serialNumber);

            $x509IssuerSerial->appendChild($x509IssuerName);
            $x509IssuerSerial->appendChild($x509SerialNumber);

            $tokenRef->appendChild($x509Data);
        } else {
            throw new \Exception('Unable to locate digital signature');
        }
    }

    public function signSoapDoc($objKey, $options = NULL) {
        $objDSig = new XMLSecurityDSig();

        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

        $arNodes = array();
        foreach ($this->secNode->childNodes AS $node) {
            if ($node->nodeType == XML_ELEMENT_NODE) {
                $arNodes[] = $node;
            }
        }

        if ($this->signAllHeaders) {
            foreach ($this->secNode->parentNode->childNodes AS $node) {
                if (($node->nodeType == XML_ELEMENT_NODE) &&
                        ($node->namespaceURI != WSSESoap::WSSENS)) {
                    $arNodes[] = $node;
                }
            }
        }

        foreach ($this->envelope->childNodes AS $node) {
            if ($node->namespaceURI == $this->soapNS && $node->localName == 'Body') {
                $arNodes[] = $node;
                break;
            }
        }

        $algorithm = XMLSecurityDSig::SHA1;
        if (is_array($options) && isset($options["algorithm"])) {
            $algorithm = $options["algorithm"];
        }

        $arOptions = array('prefix' => WSSESoap::WSUPFX, 'prefix_ns' => WSSESoap::WSUNS);
        $objDSig->addReferenceList($arNodes, $algorithm, NULL, $arOptions);

        $objDSig->sign($objKey);

        $insertTop = TRUE;
        if (is_array($options) && isset($options["insertBefore"])) {
            $insertTop = (bool) $options["insertBefore"];
        }
        $objDSig->appendSignature($this->secNode, $insertTop);

        /* New suff */
        if (is_array($options)) {
            if (!empty($options["KeyInfo"])) {
                if (!empty($options["KeyInfo"]["X509SubjectKeyIdentifier"])) {
                    $sigNode = $this->secNode->firstChild->nextSibling;
                    $objDoc = $sigNode->ownerDocument;
                    $keyInfo = $sigNode->ownerDocument->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'ds:KeyInfo');
                    $sigNode->appendChild($keyInfo);
                    $tokenRef = $objDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':SecurityTokenReference');
                    $keyInfo->appendChild($tokenRef);
                    $reference = $objDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':KeyIdentifier');
                    $reference->setAttribute("ValueType", "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509SubjectKeyIdentifier");
                    $reference->setAttribute("EncodingType", "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary");
                    $tokenRef->appendChild($reference);
                    $x509 = openssl_x509_parse($objKey->getX509Certificate());
                    $keyid = $x509["extensions"]["subjectKeyIdentifier"];
                    $arkeyid = split(":", $keyid);
                    $data = "";
                    foreach ($arkeyid AS $hexchar) {
                        $data .= chr(hexdec($hexchar));
                    }
                    $dataNode = new \DOMText(base64_encode($data));
                    $reference->appendChild($dataNode);
                }
            }
        }
    }

    public function addEncryptedKey($node, $key, $token, $options = NULL) {
        if (!$key->encKey) {
            return FALSE;
        }
        $encKey = $key->encKey;
        $security = $this->locateSecurityHeader();
        $doc = $security->ownerDocument;
        if (!$doc->isSameNode($encKey->ownerDocument)) {
            $key->encKey = $security->ownerDocument->importNode($encKey, TRUE);
            $encKey = $key->encKey;
        }
        if (!empty($key->guid)) {
            return TRUE;
        }

        $lastToken = NULL;
        $findTokens = $security->firstChild;
        while ($findTokens) {
            if ($findTokens->localName == 'BinarySecurityToken') {
                $lastToken = $findTokens;
            }
            $findTokens = $findTokens->nextSibling;
        }
        if ($lastToken) {
            $lastToken = $lastToken->nextSibling;
        }

        $security->insertBefore($encKey, $lastToken);
        $key->guid = XMLSecurityDSig::generate_GUID();
        $encKey->setAttribute('Id', $key->guid);
        $encMethod = $encKey->firstChild;
        while ($encMethod && $encMethod->localName != 'EncryptionMethod') {
            $encMethod = $encMethod->nextChild;
        }
        if ($encMethod) {
            $encMethod = $encMethod->nextSibling;
        }
        $objDoc = $encKey->ownerDocument;
        $keyInfo = $objDoc->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'dsig:KeyInfo');
        $encKey->insertBefore($keyInfo, $encMethod);
        $tokenRef = $objDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':SecurityTokenReference');
        $keyInfo->appendChild($tokenRef);
        /* New suff */
        if (is_array($options)) {
            if (!empty($options["KeyInfo"])) {
                if (!empty($options["KeyInfo"]["X509SubjectKeyIdentifier"])) {
                    $reference = $objDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':KeyIdentifier');
                    $reference->setAttribute("ValueType", "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509SubjectKeyIdentifier");
                    $reference->setAttribute("EncodingType", "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary");
                    $tokenRef->appendChild($reference);
                    $x509 = openssl_x509_parse($token->getX509Certificate());
                    $keyid = $x509["extensions"]["subjectKeyIdentifier"];
                    $arkeyid = split(":", $keyid);
                    $data = "";
                    foreach ($arkeyid AS $hexchar) {
                        $data .= chr(hexdec($hexchar));
                    }
                    $dataNode = new \DOMText(base64_encode($data));
                    $reference->appendChild($dataNode);
                    return TRUE;
                }
            }
        }

        $tokenURI = '#' . $token->getAttributeNS(WSSESoap::WSUNS, "Id");
        $reference = $objDoc->createElementNS(WSSESoap::WSSENS, WSSESoap::WSSEPFX . ':Reference');
        $reference->setAttribute("URI", $tokenURI);
        $tokenRef->appendChild($reference);

        return TRUE;
    }

    public function AddReference($baseNode, $guid) {
        $refList = NULL;
        $child = $baseNode->firstChild;
        while ($child) {
            if (($child->namespaceURI == XMLSecEnc::XMLENCNS) && ($child->localName == 'ReferenceList')) {
                $refList = $child;
                break;
            }
            $child = $child->nextSibling;
        }
        $doc = $baseNode->ownerDocument;
        if (is_null($refList)) {
            $refList = $doc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:ReferenceList');
            $baseNode->appendChild($refList);
        }
        $dataref = $doc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:DataReference');
        $refList->appendChild($dataref);
        $dataref->setAttribute('URI', '#' . $guid);
    }

    public function EncryptBody($siteKey, $objKey, $token) {

        $enc = new XMLSecEnc();
        foreach ($this->envelope->childNodes AS $node) {
            if ($node->namespaceURI == $this->soapNS && $node->localName == 'Body') {
                break;
            }
        }
        $enc->setNode($node);
        /* encrypt the symmetric key */
        $enc->encryptKey($siteKey, $objKey, FALSE);

        $enc->type = XMLSecEnc::Content;
        /* Using the symmetric key to actually encrypt the data */
        $encNode = $enc->encryptNode($objKey);

        $guid = XMLSecurityDSig::generate_GUID();
        $encNode->setAttribute('Id', $guid);

        $refNode = $encNode->firstChild;
        while ($refNode && $refNode->nodeType != XML_ELEMENT_NODE) {
            $refNode = $refNode->nextSibling;
        }
        if ($refNode) {
            $refNode = $refNode->nextSibling;
        }
        if ($this->addEncryptedKey($encNode, $enc, $token)) {
            $this->AddReference($enc->encKey, $guid);
        }
    }

    public function encryptSoapDoc($siteKey, $objKey, $options = NULL, $encryptSignature = TRUE) {

        $enc = new XMLSecEnc();

        $xpath = new \DOMXPath($this->envelope->ownerDocument);
        if ($encryptSignature == FALSE) {
            $nodes = $xpath->query('//*[local-name()="Body"]');
        } else {
            $nodes = $xpath->query('//*[local-name()="Signature"] | //*[local-name()="Body"]');
        }

        foreach ($nodes AS $node) {
            $type = XMLSecEnc::Element;
            $name = $node->localName;
            if ($name == "Body") {
                $type = XMLSecEnc::Content;
            }
            $enc->addReference($name, $node, $type);
        }

        $enc->encryptReferences($objKey);

        $enc->encryptKey($siteKey, $objKey, false);

        $nodes = $xpath->query('//*[local-name()="Security"]');
        $signode = $nodes->item(0);
        $this->addEncryptedKey($signode, $enc, $siteKey, $options);
    }

    public function decryptSoapDoc($doc, $options) {

        $privKey = NULL;
        $privKey_isFile = FALSE;
        $privKey_isCert = FALSE;

        if (is_array($options)) {
            $privKey = (!empty($options["keys"]["private"]["key"]) ? $options["keys"]["private"]["key"] : NULL);
            $privKey_isFile = (!empty($options["keys"]["private"]["isFile"]) ? TRUE : FALSE);
            $privKey_isCert = (!empty($options["keys"]["private"]["isCert"]) ? TRUE : FALSE);
        }

        $objenc = new XMLSecEnc();

        $xpath = new \DOMXPath($doc);
        $envns = $doc->documentElement->namespaceURI;
        $xpath->registerNamespace("soapns", $envns);
        $xpath->registerNamespace("soapenc", "http://www.w3.org/2001/04/xmlenc#");

        $nodes = $xpath->query('/soapns:Envelope/soapns:Header/*[local-name()="Security"]/soapenc:EncryptedKey');

        $references = array();
        if ($node = $nodes->item(0)) {
            $objenc = new XMLSecEnc();
            $objenc->setNode($node);
            if (!$objKey = $objenc->locateKey()) {
                throw new \Exception("Unable to locate algorithm for this Encrypted Key");
            }
            $objKey->isEncrypted = TRUE;
            $objKey->encryptedCtx = $objenc;
            XMLSecEnc::staticLocateKeyInfo($objKey, $node);
            if ($objKey && $objKey->isEncrypted) {
                $objencKey = $objKey->encryptedCtx;
                $objKey->loadKey($privKey, $privKey_isFile, $privKey_isCert);
                $key = $objencKey->decryptKey($objKey);
                $objKey->loadKey($key);
            }

            $refnodes = $xpath->query('./soapenc:ReferenceList/soapenc:DataReference/@URI', $node);
            foreach ($refnodes as $reference) {
                $references[] = $reference->nodeValue;
            }
        }

        foreach ($references AS $reference) {
            $arUrl = parse_url($reference);
            $reference = $arUrl['fragment'];
            $query = '//*[@Id="' . $reference . '"]';
            $nodes = $xpath->query($query);
            $encData = $nodes->item(0);

            if ($algo = $xpath->evaluate("string(./soapenc:EncryptionMethod/@Algorithm)", $encData)) {
                $objKey = new XMLSecurityKey($algo);
                $objKey->loadKey($key);
            }

            $objenc->setNode($encData);
            $objenc->type = $encData->getAttribute("Type");
            $decrypt = $objenc->decryptNode($objKey, TRUE);
        }

        return TRUE;
    }

    public function saveXML() {
        return $this->soapDoc->saveXML();
    }

    public function save($file) {
        return $this->soapDoc->save($file);
    }

}
