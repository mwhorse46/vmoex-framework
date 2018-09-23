<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-23 15:27:12
 */

namespace Yeskn\Support\Http;

/**
 * Trait HttpError
 * @package Yeskn\Support\Http
 */
trait HttpResponse
{
    public function errorResponse($msg)
    {
        $isXhr = $this->get('request_stack')->getCurrentRequest()->isXmlHttpRequest();

        if ($isXhr) {
            return new ApiFail($msg);
        }

        return $this->render('@YesknMain/error.html.twig', [
            'message' => $msg
        ]);
    }
}