<?php
/**
 * Licensed to The Apereo Foundation under one or more contributor license
 * agreements. See the NOTICE file distributed with this work for
 * additional information regarding copyright ownership.

 * The Apereo Foundation licenses this file to you under the Apache License,
 * Version 2.0 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.

 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
 
/**
 * Use this to validate the mime type of a file.
 */
class Xerte_Validate_FileMimeType {

    public static $allowableMimeTypeList = array();

    protected $messages = array();

    /**
     * we need to know if the PHP env supports this validator.
     */
    public static function canRun() {
        return function_exists('mime_content_type');
    }

    /**
     * @return boolean true if ok.
     * @param string file name. e.g. /etc/passwd, /usr/bin/blah, c:/blah, /tmp/php_upload/blah
     */
    public function isValid($file_name) {
        $this->messages = array();
        if(self::canRun()) {
            if(file_exists($file_name)) {
                $mime_type = mime_content_type($file_name);
                if(in_array($mime_type, self::$allowableMimeTypeList)) {
                    return true;
                }
                $this->messages['INVALID_MIME_TYPE'] = "MIME type $mime_type is not an allowed type";
            }
            else {
                $this->messages['FILE_NOT_FOUND'] = "File not found - $file_name";
            }
        }
        else {
            $this->messages['UNSUPPORTED'] = "Can't run - function: mime_content_type not found";
        }

        return false;
    }

    /**
     * @return array of error messages (if any).
     */
    public function getMessages() {
        return $this->messages;
    }
    public function getErrors() {
        return array_keys($this->messages);
    }
}
