<?php
/**
 * Thumb()
 * A TimThumb-style function to generate image thumbnails on the fly.
 *
 * @access public
 * @param string $url
 * @return Boolean
 *
 */
    function doesUrlLoad($url)
    {

        $file_headers = @get_headers($url);

        if (!$file_headers || (strpos($file_headers[0], '200') == false) )
        {
            return false;
        }
        else
        {
            return true;
        }

    }