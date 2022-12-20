<?php
return [
    //Plan
    'plan_view_internet' => ['/internet', '/internet/table'],
    'plan_add_internet' => ['/internet/crear', '/internet/add', '/internet/success/'],
    'plan_edit_internet' => ['/internet/editar', '/internet/update', '/internet/success/'],
    'plan_delete_internet' => ['/internet/destroy'],

    'plan_view_paquetes' => ['/paquetes', '/paquetes/table'],
    'plan_add_paquetes' => ['/paquetes/crear', '/paquetes/add', '/paquetes/success/'],
    'plan_edit_paquetes' => ['/paquetes/editar', '/paquetes/update', '/paquetes/success/'],
    'plan_delete_paquetes' => ['/paquetes/destroy'],

    'plan_view_voz' => ['/voz', '/voz/table'],
    'plan_add_voz' => ['/voz/crear', '/voz/add', '/voz/success/'],
    'plan_edit_voz' => ['/voz/editar', '/voz/update', '/voz/success/'],
    'plan_delete_voz' => ['/voz/destroy'],

    'plan_view_custom' => ['/custom', '/custom/table'],
    'plan_add_custom' => ['/custom/crear', '/custom/add', '/custom/success/'],
    'plan_edit_custom' => ['/custom/editar', '/custom/update', '/custom/success/'],
    'plan_delete_custom' => ['/custom/destroy'],

    //Crm
    'crm_dashboard' => ['/crm/dashboard'],
    'crm_view_crm' => ['/crm/listar', '/crm/table'],
    'crm_add_crm' => ['/crm/crear', '/crm/add', '/crm/success/'],
    'crm_edit_crm' => ['/crm/editar', '/crm/update', '/crm/success/'],
    'crm_delete_crm' => ['/crm/destroy'],

    'crm_document_view_crm' => ['/crm/document/listar', '/crm/document/table'],
    'crm_document_add_crm' => ['/crm/document/crear', '/crm/document/add', '/crm/document/success/'],
    'crm_document_edit_crm' => ['/crm/document/editar', '/crm/document/update', '/crm/document/success/'],
    'crm_document_delete_crm' => ['/crm/document/destroy'],

    //Client
    'client_dashboard' => ['/cliente/dashboard'],
    'client_view_client' => ['/cliente/listar', '/cliente/table'],
    'client_add_client' => ['/cliente/crear', '/cliente/add', '/cliente/success/'],
    'client_edit_client' => ['/cliente/editar', '/cliente/update', '/cliente/success/'],
    'client_delete_client' => ['/cliente/destroy'],

    'client_service_internet_view_client' => ['/cliente/clientinternetservice/table'],
    'client_service_internet_add_client' => ['/cliente/clientinternetservice/crear'],
    'client_service_internet_edit_client' => ['/cliente/clientinternetservice/update'],
    'client_service_internet_delete_client' => ['/cliente/clientinternetservice/destroy'],

    'client_service_voz_view_client' => ['/cliente/clientvozservice/table'],
    'client_service_voz_add_client' => ['/cliente/clientvozservice/crear'],
    'client_service_voz_edit_client' => ['/cliente/clientvozservice/update'],
    'client_service_voz_delete_client' => ['/cliente/clientvozservice/destroy'],

    'client_service_bundle_view_client' => ['/cliente/clientbundleservice/table'],
    'client_service_bundle_add_client' => ['/cliente/clientbundleservice/crear'],
    'client_service_bundle_edit_client' => ['/cliente/clientbundleservice/update'],
    'client_service_bundle_delete_client' => ['/cliente/clientbundleservice/destroy'],


    //Router
    'router_view_router' => ['/red/router', '/red/router/table', '/red/router/listar'],
    'router_add_router' => ['/red/router/crear', '/red/router/add'],
    'router_edit_router' => ['/red/router/editar', '/red/router/update', '/red/router/success/'],
    'router_delete_router' => ['/red/router/destroy'],

];
