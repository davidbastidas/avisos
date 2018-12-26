<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/getIndicadores',
        'admin/avisos/upload',
        'admin/excel/upload',
        'admin/excel/download',
        'admin/download-avisos',
        'admin/carga-avisos',
        'admin/asignar-avisos',
        'admin/vaciar-carga',
        'admin/getAvisos',
        'admin/agenda/download',
        'admin/asignarall',
        'admin/avisos/save',
        'admin/dashboard/getAvancePorGestor',
        'admin/dashboard/getAvanceDiario',
        'admin/dashboard/getPointMapGestores',
        'admin/avisos/getPointMapVisita',
        'admin/avisos/delete/all',
    ];
}
